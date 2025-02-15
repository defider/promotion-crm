<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return RegionResource::collection(Region::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request): RegionResource
    {
        return new RegionResource(Region::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region): RegionResource
    {
        return new RegionResource($region);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region): RegionResource
    {
        $region->update($request->all());

        return new RegionResource($region);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region): JsonResponse
    {
        $region->delete();

        return response()->json(['message' => 'Region has been removed']);
    }
}
