<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PricingInquiry;
use Illuminate\Http\Request;

class PricingInquiryController extends Controller
{
    // Hiển thị danh sách đơn đăng ký
    public function index()
    {
        $inquiries = PricingInquiry::with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.pricing_inquiries.index', compact('inquiries'));
    }

    // Xem chi tiết
    public function show($id)
    {
        $inquiry = PricingInquiry::with('plan')->findOrFail($id);
        return view('backend.pricing_inquiries.show', compact('inquiry'));
    }

    // Xóa đơn đăng ký
    public function destroy($id)
    {
        $inquiry = PricingInquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('admin.pricing-inquiries.index')
            ->with('success', 'Đã xóa đơn đăng ký thành công!');
    }

    // Cập nhật trạng thái
    public function updateStatus(Request $request, $id)
    {
        $inquiry = PricingInquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,contacted,closed,completed,cancelled'],
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
