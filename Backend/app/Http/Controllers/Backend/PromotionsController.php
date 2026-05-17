<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index()
    {
        $promotions = Promotion::whereIn('status', ['active', 'inactive'])->get();

        return view('backend.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('backend.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        Promotion::create([
            'code'        => $request->code,
            'type'        => $request->type,
            'value'       => $request->value,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'min_order'   => $request->min_order,
            'usage_limit' => $request->usage_limit,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        return view('backend.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code,' . $promotion->id,
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $promotion->update([
            'code'        => $request->code,
            'type'        => $request->type,
            'value'       => $request->value,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'min_order'   => $request->min_order,
            'usage_limit' => $request->usage_limit,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }

    public function toggleStatus(Promotion $promotion)
    {
        $promotion->status =
            $promotion->status == 'active'
            ? 'inactive'
            : 'active';

        $promotion->save();

        return redirect()
            ->back()
            ->with('success', 'Status updated successfully.');
    }
}