<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;

class PricingPlanController extends Controller
{
    public function index()
    {
        $plans = PricingPlan::orderBy('order')->get();
        return view('backend.pricing.index', compact('plans'));
    }

    public function create()
    {
        return view('backend.pricing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:100',
            'description'         => 'nullable|string|max:255',
            'price'               => 'nullable|numeric|min:0',
            'price_note'          => 'nullable|string|max:100',
            'button_text'         => 'required|string|max:50',
            'order'               => 'required|integer|min:0',
            'status'              => 'required|in:active,inactive',
            'features'            => 'nullable|array',
            'features.*'          => 'nullable|string',   // ← thêm nullable
            'disabled_features'   => 'nullable|array',
            'disabled_features.*' => 'nullable|string',   // ← thêm nullable
        ]);

        PricingPlan::create([
            'name'              => $request->name,
            'description'       => $request->description,
            'price'             => $request->price,
            'price_note'        => $request->price_note,
            'button_text'       => $request->button_text,
            'order'             => $request->order,
            'status'            => $request->status,
            'is_popular'        => $request->boolean('is_popular'),
            'features'          => array_values(array_filter($request->features ?? [])),          // ← lọc rỗng
            'disabled_features' => array_values(array_filter($request->disabled_features ?? [])), // ← lọc rỗng
        ]);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Tạo gói giá thành công!');
    }

    public function edit(PricingPlan $pricing)
    {
        return view('backend.pricing.edit', compact('pricing'));
    }

    public function update(Request $request, PricingPlan $pricing)
    {
        $request->validate([
            'name'                => 'required|string|max:100',
            'description'         => 'nullable|string|max:255',
            'price'               => 'nullable|numeric|min:0',
            'price_note'          => 'nullable|string|max:100',
            'button_text'         => 'required|string|max:50',
            'order'               => 'required|integer|min:0',
            'status'              => 'required|in:active,inactive',
            'features'            => 'nullable|array',
            'features.*'          => 'nullable|string',   // ← thêm nullable
            'disabled_features'   => 'nullable|array',
            'disabled_features.*' => 'nullable|string',   // ← thêm nullable
        ]);

        $pricing->update([
            'name'              => $request->name,
            'description'       => $request->description,
            'price'             => $request->price,
            'price_note'        => $request->price_note,
            'button_text'       => $request->button_text,
            'order'             => $request->order,
            'status'            => $request->status,
            'is_popular'        => $request->boolean('is_popular'),
            'features'          => array_values(array_filter($request->features ?? [])),          // ← lọc rỗng
            'disabled_features' => array_values(array_filter($request->disabled_features ?? [])), // ← lọc rỗng
        ]);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Cập nhật gói giá thành công!');
    }

    public function destroy(PricingPlan $pricing)
    {
        $pricing->delete();
        return redirect()->route('admin.pricing.index')
            ->with('success', 'Xoá gói giá thành công!');
    }
}