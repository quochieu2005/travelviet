<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantApiController extends Controller
{
    public function getRestaurants(Request $request)
    {
        $query = Restaurant::orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 6);
        $restaurants = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $restaurants,
            'message' => 'Lấy danh sách nhà hàng thành công'
        ]);
    }

    public function getRestaurantBySlug($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->first();

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhà hàng này'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $restaurant,
            'message' => 'Lấy chi tiết nhà hàng thành công'
        ]);
    }
}