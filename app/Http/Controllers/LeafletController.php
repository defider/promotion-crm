<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeafletRequest;
use App\Http\Requests\UpdateLeafletRequest;
use App\Http\Resources\LeafletResource;
use App\Models\Leaflet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeafletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return LeafletResource::collection(Leaflet::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeafletRequest $request): LeafletResource
    {
        return new LeafletResource(Leaflet::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Leaflet $leaflet): LeafletResource
    {
        return new LeafletResource($leaflet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeafletRequest $request, Leaflet $leaflet): LeafletResource
    {
        $leaflet->update($request->all());

        return new LeafletResource($leaflet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leaflet $leaflet): JsonResponse
    {
        $leaflet->delete();

        return response()->json(['message' => 'Leaflet has been removed']);
    }
}
