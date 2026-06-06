<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Gửi liên hệ
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $customer = auth('sanctum')->user();

        Contact::create([
            'user_id' => $customer?->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gửi liên hệ thành công'
        ], 201);
    }

    /**
     * Danh sách liên hệ (Admin)
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);

        return response()->json($contacts);
    }

    /**
     * Chi tiết liên hệ
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return response()->json($contact);
    }

    /**
     * Cập nhật trạng thái
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,replied'
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công'
        ]);
    }

    /**
     * Phản hồi khách hàng
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update([
            'reply'      => $request->reply,
            'status'     => 'replied',
            'replied_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Phản hồi thành công'
        ]);
    }
}
