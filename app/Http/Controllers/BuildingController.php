<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Building::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBuildingRequest $request)
    {
        return Building::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building): Building
    {
        return $building;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuildingRequest $request, Building $building): Building
    {
        $building->update($request->all());

        return $building;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building): \Illuminate\Http\JsonResponse
    {
        $building->delete();

        return response()->json(['message' => 'Building has been removed']);
    }
}
