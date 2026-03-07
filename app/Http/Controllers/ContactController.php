<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;

class ContactController extends Controller
{
    // Show contact form
    public function create()
    {
        $contactInfo = ContactInfo::first();
        return view('pages.contact', compact('contactInfo'));
    }

    // Store contact message
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create($request->all());

        return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }

    // Admin: List all contacts
    public function adminIndex()
    {
        $contacts = Contact::with('repliedBy')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    // Admin: View contact detail
    public function adminShow($id)
    {
        $contact = Contact::with('repliedBy')->findOrFail($id);
        
        // Mark as read
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    // Admin: Reply to contact
    public function adminReply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);

        $contact = Contact::findOrFail($id);
        
        $contact->update([
            'admin_reply' => $request->admin_reply,
            'replied_by' => auth()->id(),
            'replied_at' => now(),
        ]);

        // Send email to customer with reply
        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact));
            return redirect()->back()->with('success', 'Đã gửi phản hồi qua email thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Đã lưu phản hồi nhưng không thể gửi email: ' . $e->getMessage());
        }
    }

    // Admin: Delete contact
    public function adminDestroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa liên hệ!');
    }

    // Admin: Edit contact info
    public function adminEditInfo()
    {
        $contactInfo = ContactInfo::first();
        return view('admin.contact-info.edit', compact('contactInfo'));
    }

    // Admin: Update contact info
    public function adminUpdateInfo(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'hotline' => 'required|string|max:50',
            'phone' => 'nullable|string|max:50',
            'working_hours' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'support_email' => 'nullable|email|max:255',
            'weekday_hours' => 'nullable|string|max:255',
            'weekend_hours' => 'nullable|string|max:255',
            'holiday_note' => 'nullable|string|max:255',
            'map_embed_url' => 'nullable|url',
        ]);

        $contactInfo = ContactInfo::first();
        $contactInfo->update($request->all());

        return redirect()->back()->with('success', 'Đã cập nhật thông tin liên hệ!');
    }
}
