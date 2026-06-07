<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Transport;
use Illuminate\Http\Request;

class TransportApiController extends Controller
{
    // 1. Lấy danh sách phương tiện (Phân trang + Bộ lọc)
    public function getTransports(Request $request)
    {
        $query = Transport::with('destination')
            ->orderBy('id', 'desc');

        // Lọc theo id_destination
        if ($request->filled('destination_id')) {
            $query->where('id_destination', $request->destination_id);
        }

        // Lọc theo slug của điểm đến
        if ($request->filled('destination_slug')) {
            $query->whereHas('destination', function($q) use ($request) {
                $q->where('slug', $request->destination_slug);
            });
        }

        // Lọc theo hộp số (Automatic / Manual)
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        // Tìm kiếm theo tên xe
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 6);
        
        // Sử dụng through() trực tiếp trên paginate để chuẩn hóa JSON an toàn
        $transports = $query->paginate($perPage)->through(function($transport) {
            return [
                'id' => $transport->id,
                'name' => $transport->name,
                'slug' => $transport->slug,
                'mileage' => $transport->mileage,
                'transmission' => $transport->transmission,
                'seats' => $transport->seats ? (int)$transport->seats : null,
                'trips' => $transport->trips ? (int)$transport->trips : 0,
                'rating' => $transport->rating ? (float)$transport->rating : null,
                'review' => $transport->review ? (int)$transport->review : 0,
                'price' => (float)$transport->price,
                'image' => $transport->image,
                'destination' => [
                    'id' => $transport->destination?->id,
                    'name' => $transport->destination?->name,
                    'slug' => $transport->destination?->slug,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transports,
            'message' => 'Lấy danh sách phương tiện thành công'
        ]);
    }

    // 2. Lấy chi tiết một phương tiện dựa vào Slug
    public function getTransportBySlug($slug)
    {
        $transport = Transport::with('destination')
            ->where('slug', $slug)
            ->first();

        if (!$transport) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phương tiện này'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transport->id,
                'name' => $transport->name,
                'slug' => $transport->slug,
                'mileage' => $transport->mileage,
                'transmission' => $transport->transmission,
                'seats' => $transport->seats ? (int)$transport->seats : null,
                'trips' => $transport->trips ? (int)$transport->trips : 0,
                'rating' => $transport->rating ? (float)$transport->rating : null,
                'review' => $transport->review ? (int)$transport->review : 0,
                'price' => (float)$transport->price,
                'image' => $transport->image,
                'destination' => [
                    'id' => $transport->destination?->id,
                    'name' => $transport->destination?->name,
                    'slug' => $transport->destination?->slug,
                ]
            ],
            'message' => 'Lấy chi tiết phương tiện thành công'
        ]);
    }

    // 3. Lấy danh sách các điểm đến có chứa phương tiện
    public function getTransportDestinations()
    {
        $destinations = Destination::whereHas('transports')
            ->orderBy('name')
            ->get()
            ->map(function($destination) {
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'slug' => $destination->slug,
                    'transport_count' => $destination->transports()->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $destinations,
            'message' => 'Lấy danh sách điểm đến của phương tiện thành công'
        ]);
    }

    // 4. Lấy các phương tiện liên quan
    public function getRelatedTransports($id, Request $request)
    {
        $limit = $request->get('limit', 3);
        $currentTransport = Transport::find($id);

        if (!$currentTransport) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phương tiện gốc'
            ], 404);
        }

        $related = Transport::with('destination')
            ->where('id', '!=', $id)
            ->where('id_destination', $currentTransport->id_destination)
            ->limit($limit)
            ->get()
            ->map(function($transport) {
                return [
                    'id' => $transport->id,
                    'name' => $transport->name,
                    'slug' => $transport->slug,
                    'price' => (float)$transport->price,
                    'image' => $transport->image,
                    'rating' => $transport->rating ? (float)$transport->rating : null,
                    'transmission' => $transport->transmission,
                    'destination' => [
                        'name' => $transport->destination?->name,
                        'slug' => $transport->destination?->slug,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $related,
            'message' => 'Lấy danh sách phương tiện liên quan thành công'
        ]);
    }
}