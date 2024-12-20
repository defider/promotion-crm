<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeafletRequest;
use App\Http\Requests\UpdateLeafletRequest;
use App\Models\Leaflet;

class LeafletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Leaflet::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeafletRequest $request)
    {
        return Leaflet::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Leaflet $leaflet): Leaflet
    {
        return $leaflet;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeafletRequest $request, Leaflet $leaflet): Leaflet
    {
        $leaflet->update($request->all());

        return $leaflet;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leaflet $leaflet): \Illuminate\Http\JsonResponse
    {
        $leaflet->delete();

        return response()->json(['message' => 'Leaflet has been removed']);
    }
}
