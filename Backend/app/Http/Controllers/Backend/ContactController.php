<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::when($request->search, function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('subject', 'like', '%' . $request->search . '%');
        })
            ->latest()
            ->paginate(15);

        return view('backend.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('backend.contacts.show', compact('contact'));
    }

    public function replyForm($id)  // Hoặc tên method bạn thích
    {
        $contact = Contact::findOrFail($id);
        return view('backend.contacts.reply', compact('contact'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:10'
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update([
            'reply' => $request->reply,
            'status' => 'replied',
            'replied_at' => now()
        ]);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Phản hồi đã được gửi thành công!');
    }
}
