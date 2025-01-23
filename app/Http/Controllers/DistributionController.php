<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Models\Distribution;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Distribution::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistributionRequest $request): \Illuminate\Http\JsonResponse
    {
        Distribution::create($request->all());

        return response()->json(['message' => 'Distribution began'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Distribution $distribution): Distribution
    {
        return $distribution;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistributionRequest $request, Distribution $distribution): Distribution
    {
        $distribution->update($request->all());

        return $distribution;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distribution $distribution): \Illuminate\Http\JsonResponse
    {
        $distribution->delete();

        return response()->json(['message' => 'Distribution has been removed']);
    }

    public function end($id): \Illuminate\Http\JsonResponse
    {
        $distribution = Distribution::findOrFail($id);

        if ($distribution->ended_at) {
            return response()->json([
                'message' => 'Distribution already ended',
            ], 400);
        }

        $distribution->ended_at = now();
        $distribution->save();

        return response()->json([
            'message' => 'Distribution ended',
        ]);
    }
}
