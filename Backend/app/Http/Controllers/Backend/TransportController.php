<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransportController extends Controller
{
    public function index()
    {
        $transports = Transport::with('destination')
            ->latest()
            ->paginate(10);

        return view('backend.transports.index', compact('transports'));
    }

    public function create()
    {
        $destinations = Destination::all();

        return view('backend.transports.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'id_destination' => 'required|exists:destinations,id',
            'mileage' => 'nullable',
            'transmission' => 'nullable',
            'trips' => 'nullable|integer',
            'seats' => 'nullable|integer',
            'rating' => 'nullable|numeric',
            'review' => 'nullable|integer',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Transport::create($data);

        return redirect()
            ->route('admin.transports.index')
            ->with('success', 'Thêm transport thành công');
    }

    public function edit(Transport $transport)
    {
        $destinations = Destination::all();

        return view('backend.transports.edit',
            compact('transport', 'destinations')
        );
    }

    public function update(Request $request, Transport $transport)
    {
        $data = $request->validate([
            'name' => 'required',
            'id_destination' => 'required|exists:destinations,id',
            'mileage' => 'nullable',
            'transmission' => 'nullable',
            'trips' => 'nullable|integer',
            'seats' => 'nullable|integer',
            'rating' => 'nullable|numeric',
            'review' => 'nullable|integer',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $transport->update($data);

        return redirect()
            ->route('admin.transports.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(Transport $transport)
    {
        $transport->delete();

        return back()->with('success', 'Xóa thành công');
    }
}