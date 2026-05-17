<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;

class ToursController extends Controller
{
    public function index()
    {
        return view('backend.tours.index');
    }

    public function create()
    {
        return view('backend.tours.create');
    }

    public function edit(Tour $tour)
    {
        return view('backend.tours.edit', compact('tour'));
    }
}
