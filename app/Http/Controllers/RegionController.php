<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\Region;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Region::all();

        //return response()->json(['message' => 'regions list']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request)
    {
        return Region::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region): Region
    {
        return $region;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region): Region
    {
        $region->update($request->all());

        return $region;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region): \Illuminate\Http\JsonResponse
    {
        $region->delete();

        return response()->json(['message' => 'Region has been removed']);
    }
}
