<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
    // Thời gian coi admin là "đang hoạt động" (giây)
    const ADMIN_ONLINE_TTL = 300; // 5 phút

    // User sends message
    public function store(Request $request)
    {
        $rules = [
            'message' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:5120',
        ];

        if (!auth()->check()) {
            $rules['guest_name']  = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        if (empty($request->message) && !$request->hasFile('image')) {
            return response()->json(['success' => false, 'error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh.'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('messages', 'public');
        }

        $message = Message::create([
            'user_id'        => auth()->id(),
            'guest_name'     => $request->guest_name,
            'guest_email'    => $request->guest_email,
            'message'        => $request->message ?? '',
            'image'          => $imagePath,
            'is_admin_reply' => false,
        ]);

        $this->maybeAutoReply($message, $request);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Quyết định có gửi auto-reply không:
     * 1. Admin KHÔNG online → luôn auto-reply
     * 2. Admin ĐANG online nhưng chưa đọc → chỉ auto-reply nếu đây là tin nhắn đầu tiên trong ngày
     */
    private function maybeAutoReply(Message $message, Request $request)
    {
        $adminOnline = Cache::has('admin_online');
        $userId      = $message->user_id;
        $guestEmail  = $message->guest_email;

        // Kiểm tra đã có auto-reply chưa đọc chưa (tránh spam)
        $alreadyAutoReplied = Message::where('is_admin_reply', true)
            ->where('replied_by', null) // null = auto-reply
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId && $guestEmail, fn($q) => $q->where('guest_email', $guestEmail))
            ->whereDate('created_at', today())
            ->exists();

        $shouldReply = false;

        if (!$adminOnline) {
            // Admin không online → luôn reply (nếu hôm nay chưa auto-reply)
            $shouldReply = !$alreadyAutoReplied;
        } else {
            // Admin online nhưng tin nhắn chưa được đọc → reply nếu là tin đầu tiên trong ngày
            $isFirstTodayMessage = !Message::where('is_admin_reply', false)
                ->when($userId, fn($q) => $q->where('user_id', $userId))
                ->when(!$userId && $guestEmail, fn($q) => $q->where('guest_email', $guestEmail))
                ->whereDate('created_at', today())
                ->where('id', '<', $message->id)
                ->exists();

            $shouldReply = $isFirstTodayMessage && !$alreadyAutoReplied;
        }

        if ($shouldReply) {
            Message::create([
                'user_id'        => $message->user_id,
                'guest_name'     => $message->guest_name,
                'guest_email'    => $message->guest_email,
                'message'        => 'Cảm ơn bạn đã liên hệ, trong lúc chờ phản hồi bạn có thể gửi mã sản phẩm để shop hỗ trợ nhanh hơn nhé 😊',
                'image'          => null,
                'is_admin_reply' => true,
                'replied_by'     => null,
            ]);
        }
    }

    // Get user's messages
    public function index(Request $request)
    {
        $query = Message::with(['user', 'repliedBy'])
            ->orderBy('created_at', 'asc');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $email = $request->input('email');
            if ($email) {
                $query->where('guest_email', $email);
            } else {
                return response()->json(['messages' => []]);
            }
        }

        $messages = $query->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    // User view messages page
    public function userIndex()
    {
        return view('messages.index');
    }

    // Admin views all messages
    public function adminIndex()
    {
        // Đánh dấu admin đang online
        Cache::put('admin_online', true, self::ADMIN_ONLINE_TTL);

        $messages = Message::with(['user', 'repliedBy'])
            ->userMessages()
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    // Admin views conversation
    public function adminShow($id)
    {
        // Đánh dấu admin đang online
        Cache::put('admin_online', true, self::ADMIN_ONLINE_TTL);
        $firstMessage = Message::findOrFail($id);
        
        // Get all messages from this user/guest
        if ($firstMessage->user_id) {
            $messages = Message::where('user_id', $firstMessage->user_id)
                ->with(['user', 'repliedBy'])
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $messages = Message::where('guest_email', $firstMessage->guest_email)
                ->with(['user', 'repliedBy'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // Mark user messages as read
        Message::where('user_id', $firstMessage->user_id)
            ->orWhere('guest_email', $firstMessage->guest_email)
            ->where('is_admin_reply', false)
            ->update(['is_read' => true]);

        return view('admin.messages.show', compact('messages', 'firstMessage'));
    }

    // Admin replies
    public function adminReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:5120',
        ]);

        if (empty($request->message) && !$request->hasFile('image')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh.'], 422);
            }
            return redirect()->back()->with('error', 'Vui lòng nhập tin nhắn hoặc chọn ảnh.');
        }

        $originalMessage = Message::findOrFail($id);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('messages', 'public');
        }

        $reply = Message::create([
            'user_id'        => $originalMessage->user_id,
            'guest_name'     => $originalMessage->guest_name,
            'guest_email'    => $originalMessage->guest_email,
            'message'        => $request->message ?? '',
            'image'          => $imagePath,
            'is_admin_reply' => true,
            'replied_by'     => auth()->id(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $reply
            ]);
        }

        return redirect()->back()->with('success', 'Đã gửi tin nhắn!');
    }
}
