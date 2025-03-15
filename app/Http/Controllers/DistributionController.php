<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Http\Resources\DistributionResource;
use App\Models\Distribution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return DistributionResource::collection(Distribution::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistributionRequest $request): JsonResponse
    {
        new DistributionResource(Distribution::create($request->all()));

        return response()->json(['message' => 'Distribution began'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Distribution $distribution): DistributionResource
    {
        return new DistributionResource($distribution);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistributionRequest $request, Distribution $distribution): DistributionResource
    {
        $distribution->update($request->all());

        return new DistributionResource($distribution);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distribution $distribution): JsonResponse
    {
        $distribution->delete();

        return response()->json(['message' => 'Distribution has been removed']);
    }

    public function end($id): JsonResponse
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
