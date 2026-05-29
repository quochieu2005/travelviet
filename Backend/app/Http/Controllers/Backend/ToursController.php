<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToursController extends Controller
{
    public function index()
    {
        $tours = Tour::with('destination', 'category')->latest()->paginate(10);
        return view('backend.tours.index', compact('tours'));
    }

    public function create()
    {
        $destinations = Destination::where('status', 'active')->get();
        $categories   = Category::where('status', 'active')->get();
        return view('backend.tours.create', compact('destinations', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'                        => 'required|string|max:255',
            'slug'                         => 'nullable|string|max:255|unique:tours,slug',
            'description'                  => 'nullable|string',
            'short_description'            => 'nullable|string',
            'price_adult'                  => 'required|numeric|min:0',
            'price_child'                  => 'nullable|numeric|min:0',
            'discount_type_adult'          => 'nullable|in:percent,fixed',
            'price_discount_percent'       => 'nullable|numeric|min:0',
            'discount_amount_adult'        => 'nullable|numeric|min:0',
            'discount_type_child'          => 'nullable|in:percent,fixed',
            'price_child_discount_percent' => 'nullable|numeric|min:0',
            'discount_amount_child'        => 'nullable|numeric|min:0',
            'availability'                 => 'nullable|integer|min:0',
            'itinerary'                    => 'nullable|string',
            'start_date'                   => 'nullable|date',
            'end_date'                     => 'nullable|date|after_or_equal:start_date',
            'max_people'                   => 'nullable|integer|min:1',
            'duration_days'                => 'nullable|integer|min:1',
            'departure_location'           => 'nullable|string|max:255',
            'destination_id'               => 'required|exists:destinations,id',
            'category_id'                  => 'required|exists:categories,id',
            'status'                       => 'nullable|boolean',
            'meta_title'                   => 'nullable|string',
            'meta_description'             => 'nullable|string',
            'included_services'            => 'nullable|array',
            'excluded_services'            => 'nullable|array',
        ]);

        $includedServices = $request->has('included_services') && !empty($request->input('included_services')[0])
            ? array_values(array_filter(array_map('trim', explode("\n", $request->input('included_services')[0]))))
            : null;

        $excludedServices = $request->has('excluded_services') && !empty($request->input('excluded_services')[0])
            ? array_values(array_filter(array_map('trim', explode("\n", $request->input('excluded_services')[0]))))
            : null;

        // ---- Người lớn ----
        $priceAdult        = (int) $request->input('price_adult', 0);
        $discountTypeAdult = $request->input('discount_type_adult', 'percent');

        if ($discountTypeAdult === 'percent') {
            // Lưu số % vào price_discount_percent
            $discountValueAdult = max(0, min(100, (int) $request->input('price_discount_percent', 0)));
            $discountPriceAdult = $priceAdult > 0 && $discountValueAdult > 0
                ? (int) round($priceAdult * (1 - $discountValueAdult / 100))
                : $priceAdult;
        } else {
            // Lưu số tiền VNĐ vào price_discount_percent
            $discountValueAdult = max(0, (int) $request->input('discount_amount_adult', 0));
            $discountPriceAdult = $priceAdult > 0 && $discountValueAdult > 0
                ? max(0, $priceAdult - $discountValueAdult)
                : $priceAdult;
        }

        // ---- Trẻ em ----
        $priceChild        = (int) $request->input('price_child', 0);
        $discountTypeChild = $request->input('discount_type_child', 'percent');

        if ($priceChild > 0) {
            if ($discountTypeChild === 'percent') {
                $discountValueChild = max(0, min(100, (int) $request->input('price_child_discount_percent', 0)));
                $discountPriceChild = $discountValueChild > 0
                    ? (int) round($priceChild * (1 - $discountValueChild / 100))
                    : $priceChild;
            } else {
                $discountValueChild = max(0, (int) $request->input('discount_amount_child', 0));
                $discountPriceChild = $discountValueChild > 0
                    ? max(0, $priceChild - $discountValueChild)
                    : $priceChild;
            }
        } else {
            $discountValueChild = 0;
            $discountPriceChild = 0;
        }

        $slug = $request->input('slug') ?: Str::slug($request->input('title'));

        Tour::create([
            'title'                        => $request->input('title'),
            'slug'                         => $slug,
            'description'                  => $request->input('description'),
            'short_description'            => $request->input('short_description'),
            'price_adult'                  => $priceAdult,
            'price_child'                  => $priceChild,
            'price_discount_percent'       => $discountValueAdult,
            'price_child_discount_percent' => $discountValueChild,
            'discount_price'               => $discountPriceAdult,
            'discount_price_child'         => $discountPriceChild,
            'availability'                 => (int) $request->input('availability', 0),
            'itinerary'                    => $request->input('itinerary'),
            'start_date'                   => $request->input('start_date'),
            'end_date'                     => $request->input('end_date'),
            'max_people'                   => (int) $request->input('max_people', 0),
            'duration_days'                => (int) $request->input('duration_days', 0),
            'departure_location'           => $request->input('departure_location'),
            'destination_id'               => $request->input('destination_id'),
            'category_id'                  => $request->input('category_id'),
            'status'                       => $request->boolean('status', true),
            'views'                        => 0,
            'meta_title'                   => $request->input('meta_title'),
            'meta_description'             => $request->input('meta_description'),
            'included_services'            => $includedServices,
            'excluded_services'            => $excludedServices,
        ]);

        return redirect()->route('admin.tours.index')->with('success', 'Tour created successfully.');
    }

    public function edit(Tour $tour)
    {
        $destinations = Destination::where('status', 'active')->get();
        $categories   = Category::where('status', 'active')->get();
        return view('backend.tours.edit', compact('tour', 'destinations', 'categories'));
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'title'                        => 'required|string|max:255',
            'slug'                         => 'nullable|string|max:255|unique:tours,slug,' . $tour->id,
            'description'                  => 'nullable|string',
            'short_description'            => 'nullable|string',
            'price_adult'                  => 'required|numeric|min:0',
            'price_child'                  => 'nullable|numeric|min:0',
            'discount_type_adult'          => 'nullable|in:percent,fixed',
            'price_discount_percent'       => 'nullable|numeric|min:0',
            'discount_amount_adult'        => 'nullable|numeric|min:0',
            'discount_type_child'          => 'nullable|in:percent,fixed',
            'price_child_discount_percent' => 'nullable|numeric|min:0',
            'discount_amount_child'        => 'nullable|numeric|min:0',
            'availability'                 => 'nullable|integer|min:0',
            'itinerary'                    => 'nullable|string',
            'start_date'                   => 'nullable|date',
            'end_date'                     => 'nullable|date|after_or_equal:start_date',
            'max_people'                   => 'nullable|integer|min:1',
            'duration_days'                => 'nullable|integer|min:1',
            'departure_location'           => 'nullable|string|max:255',
            'destination_id'               => 'required|exists:destinations,id',
            'category_id'                  => 'required|exists:categories,id',
            'status'                       => 'nullable|boolean',
            'meta_title'                   => 'nullable|string',
            'meta_description'             => 'nullable|string',
            'included_services'            => 'nullable|array',
            'excluded_services'            => 'nullable|array',
        ]);

        $includedServices = $request->has('included_services') && !empty($request->input('included_services')[0])
            ? array_values(array_filter(array_map('trim', explode("\n", $request->input('included_services')[0]))))
            : null;

        $excludedServices = $request->has('excluded_services') && !empty($request->input('excluded_services')[0])
            ? array_values(array_filter(array_map('trim', explode("\n", $request->input('excluded_services')[0]))))
            : null;

        // ---- Người lớn ----
        $priceAdult        = (int) $request->input('price_adult', 0);
        $discountTypeAdult = $request->input('discount_type_adult', 'percent');

        if ($discountTypeAdult === 'percent') {
            $discountValueAdult = max(0, min(100, (int) $request->input('price_discount_percent', 0)));
            $discountPriceAdult = $priceAdult > 0 && $discountValueAdult > 0
                ? (int) round($priceAdult * (1 - $discountValueAdult / 100))
                : $priceAdult;
        } else {
            $discountValueAdult = max(0, (int) $request->input('discount_amount_adult', 0));
            $discountPriceAdult = $priceAdult > 0 && $discountValueAdult > 0
                ? max(0, $priceAdult - $discountValueAdult)
                : $priceAdult;
        }

        // ---- Trẻ em ----
        $priceChild        = (int) $request->input('price_child', 0);
        $discountTypeChild = $request->input('discount_type_child', 'percent');

        if ($priceChild > 0) {
            if ($discountTypeChild === 'percent') {
                $discountValueChild = max(0, min(100, (int) $request->input('price_child_discount_percent', 0)));
                $discountPriceChild = $discountValueChild > 0
                    ? (int) round($priceChild * (1 - $discountValueChild / 100))
                    : $priceChild;
            } else {
                $discountValueChild = max(0, (int) $request->input('discount_amount_child', 0));
                $discountPriceChild = $discountValueChild > 0
                    ? max(0, $priceChild - $discountValueChild)
                    : $priceChild;
            }
        } else {
            $discountValueChild = 0;
            $discountPriceChild = 0;
        }

        $slug = $request->input('slug') ?: Str::slug($request->input('title'));

        $tour->update([
            'title'                        => $request->input('title'),
            'slug'                         => $slug,
            'description'                  => $request->input('description'),
            'short_description'            => $request->input('short_description'),
            'price_adult'                  => $priceAdult,
            'price_child'                  => $priceChild,
            'price_discount_percent'       => $discountValueAdult,
            'price_child_discount_percent' => $discountValueChild,
            'discount_price'               => $discountPriceAdult,
            'discount_price_child'         => $discountPriceChild,
            'availability'                 => (int) $request->input('availability', 0),
            'itinerary'                    => $request->input('itinerary'),
            'start_date'                   => $request->input('start_date'),
            'end_date'                     => $request->input('end_date'),
            'max_people'                   => (int) $request->input('max_people', 0),
            'duration_days'                => (int) $request->input('duration_days', 0),
            'departure_location'           => $request->input('departure_location'),
            'destination_id'               => $request->input('destination_id'),
            'category_id'                  => $request->input('category_id'),
            'status'                       => $request->boolean('status', true),
            'meta_title'                   => $request->input('meta_title'),
            'meta_description'             => $request->input('meta_description'),
            'included_services'            => $includedServices,
            'excluded_services'            => $excludedServices,
        ]);

        return redirect()->route('admin.tours.index')->with('success', 'Tour updated successfully.');
    }

    public function destroy(Tour $tour)
    {
        $tour->delete();
        return redirect()->route('admin.tours.index')->with('success', 'Tour deleted successfully.');
    }

    public function toggleStatus(Tour $tour)
    {
        $tour->status = !$tour->status;
        $tour->save();
        return redirect()->route('admin.tours.index')->with('success', 'Tour status updated successfully.');
    }
}
