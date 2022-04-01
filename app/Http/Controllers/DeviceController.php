<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $devices = QueryBuilder::for(Device::class)
            ->allowedFilters('user_id')
            ->defaultSort('-created_at')
            ->get();

        return response()->json($devices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $device = Device::firstOrCreate([
            'user_id' => auth()->id(),
            'user_agent' => $request->userAgent(),
            'ips' => $request->ip(),
        ]);

        return response()->json($device);
    }

    /**
     * Display the specified resource.
     *
     * @param  Device  $device
     * @return JsonResponse
     */
    public function show(Device $device): JsonResponse
    {
        return response()->json($device);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Device  $device
     * @return Response
     */
    public function destroy(Device $device): Response
    {
        $device->delete();

        return response()->noContent();
    }
}
