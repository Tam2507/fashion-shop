<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // User sends message
    public function store(Request $request)
    {
        $rules = [
            'message' => 'required|string|max:1000',
        ];

        // Only require guest info if not logged in
        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        $message = Message::create([
            'user_id' => auth()->id(),
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'message' => $request->message,
            'is_admin_reply' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
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
            'message' => 'required|string|max:1000',
        ]);

        $originalMessage = Message::findOrFail($id);

        $reply = Message::create([
            'user_id' => $originalMessage->user_id,
            'guest_name' => $originalMessage->guest_name,
            'guest_email' => $originalMessage->guest_email,
            'message' => $request->message,
            'is_admin_reply' => true,
            'replied_by' => auth()->id(),
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
