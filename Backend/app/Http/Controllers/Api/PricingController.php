<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\PricingInquiry;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    // GET /api/pricing-plans — React gọi để lấy danh sách gói
    public function index()
    {
        $plans = PricingPlan::where('status', 'active')
            ->orderBy('order')
            ->get();

        return response()->json(['success' => true, 'data' => $plans]);
    }

    // POST /api/pricing-inquiries — Khách gửi form đăng ký
    public function store(Request $request)
    {
        $request->validate([
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:20',
            'message'         => 'nullable|string|max:1000',
        ]);

        $inquiry = PricingInquiry::create([
            'pricing_plan_id' => $request->pricing_plan_id,
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'message'         => $request->message,
            'status'          => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.',
            'data'    => $inquiry,
        ], 201);
    }
}