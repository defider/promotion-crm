<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Models\Apartment;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Apartment::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {
        return Apartment::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment): Apartment
    {
        return $apartment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment): Apartment
    {
        $apartment->update($request->all());

        return $apartment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment): \Illuminate\Http\JsonResponse
    {
        $apartment->delete();

        return response()->json(['message' => 'Apartment has been removed']);
    }
}
