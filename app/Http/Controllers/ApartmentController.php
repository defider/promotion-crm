<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ApartmentResource::collection(Apartment::with('reaction')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request): ApartmentResource
    {
        return new ApartmentResource(Apartment::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment): ApartmentResource
    {
        return new ApartmentResource($apartment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment): ApartmentResource
    {
        $apartment->update($request->all());

        return new ApartmentResource($apartment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment): JsonResponse
    {
        $apartment->delete();

        return response()->json(['message' => 'Apartment has been removed']);
    }
}
