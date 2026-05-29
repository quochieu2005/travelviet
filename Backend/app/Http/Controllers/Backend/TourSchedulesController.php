<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Http\Request;

class TourSchedulesController extends Controller
{
    public function index()
    {
        $tourSchedules = TourSchedule::with('tour')->latest()->paginate(10);
        return view('backend.tour_schedules.index', compact('tourSchedules'));
    }

    public function create()
    {
        $tours = Tour::where('status', 1)->get(['id', 'title', 'max_people', 'price_adult', 'price_child']);
        return view('backend.tour_schedules.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id'              => 'required|exists:tours,id',
            'departure_date'       => 'required|date|after_or_equal:today',
            'return_date'          => 'required|date|after_or_equal:departure_date',
            'available_slots'      => 'required|integer|min:0',
            'price_override'       => 'nullable|numeric|min:0',
            'price_override_child' => 'nullable|numeric|min:0',
            'note'                 => 'nullable|string|max:255',
        ]);

        $tour = Tour::findOrFail($validated['tour_id']);

        if ($tour->start_date && $validated['departure_date'] < $tour->start_date) {
            return back()->withErrors([
                'departure_date' => 'Ngày khởi hành không được trước ngày mở bán tour (' . $tour->start_date . ').'
            ])->withInput();
        }

        if ($tour->end_date && $validated['departure_date'] > $tour->end_date) {
            return back()->withErrors([
                'departure_date' => 'Ngày khởi hành không được sau ngày kết thúc tour (' . $tour->end_date . ').'
            ])->withInput();
        }

        TourSchedule::create($validated);

        return redirect()
            ->route('admin.tour-schedules.index')
            ->with('success', 'Tạo lịch khởi hành thành công.');
    }

    public function edit($id)
    {
        $tours        = Tour::where('status', 1)->get(['id', 'title', 'max_people', 'price_adult', 'price_child']);
        $tourSchedule = TourSchedule::findOrFail($id);
        return view('backend.tour_schedules.edit', compact('tours', 'tourSchedule'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tour_id'              => 'required|exists:tours,id',
            'departure_date'       => 'required|date',
            'return_date'          => 'required|date|after_or_equal:departure_date',
            'available_slots'      => 'required|integer|min:0',
            'price_override'       => 'nullable|numeric|min:0',
            'price_override_child' => 'nullable|numeric|min:0',
            'note'                 => 'nullable|string|max:255',
        ]);

        $tour = Tour::findOrFail($validated['tour_id']);

        if ($tour->start_date && $validated['departure_date'] < $tour->start_date) {
            return back()->withErrors([
                'departure_date' => 'Ngày khởi hành không được trước ngày mở bán tour (' . $tour->start_date . ').'
            ])->withInput();
        }

        if ($tour->end_date && $validated['departure_date'] > $tour->end_date) {
            return back()->withErrors([
                'departure_date' => 'Ngày khởi hành không được sau ngày kết thúc tour (' . $tour->end_date . ').'
            ])->withInput();
        }

        $tourSchedule = TourSchedule::findOrFail($id);
        $tourSchedule->update($validated);

        return redirect()
            ->route('admin.tour-schedules.index')
            ->with('success', 'Cập nhật lịch khởi hành thành công.');
    }

    public function destroy($id)
    {
        $tourSchedule = TourSchedule::findOrFail($id);
        $tourSchedule->delete();

        return redirect()
            ->route('admin.tour-schedules.index')
            ->with('success', 'Xóa lịch khởi hành thành công.');
    }
}