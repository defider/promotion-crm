<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return BuildingResource::collection(Building::with('region')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBuildingRequest $request): BuildingResource
    {
        return new BuildingResource(Building::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building): BuildingResource
    {
        return new BuildingResource($building);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuildingRequest $request, Building $building): BuildingResource
    {
        $building->update($request->all());

        return new BuildingResource($building);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building): JsonResponse
    {
        $building->delete();

        return response()->json(['message' => 'Building has been removed']);
    }
}
