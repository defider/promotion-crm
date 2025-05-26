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
     * Display the specified resource.
     */
    public function show(Distribution $distribution): DistributionResource
    {
        return new DistributionResource($distribution);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistributionRequest $request, Distribution $distribution): JsonResponse
    {
        $updated = $request->applyChanges($distribution);

        return response()->json([
            'message' => 'Distribution updated',
            'data' => new DistributionResource($updated)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distribution $distribution): JsonResponse
    {
        $distribution->delete();

        return response()->json(['message' => 'Distribution has been removed'], 204);
    }

    public function begin(StoreDistributionRequest $request): JsonResponse
    {
        if (Distribution::where('user_id', $request->user()->id)->whereNull('ended_at')->exists()) {
            return response()->json(['message' => 'Active distribution already exists'], 400);
        }

        $distribution = $request->store();

        return response()->json([new DistributionResource($distribution->load('building.apartments')
        )], 201);
    }

    public function current(): JsonResponse
    {
        $distribution = Distribution::with(['building', 'apartments'])
            ->where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if (! $distribution) {
            return response()->json(['message' => 'No active distribution'], 404);
        }

        return response()->json(new DistributionResource($distribution));
    }

    public function end(Distribution $distribution): JsonResponse
    {
        if ($distribution->ended_at) {
            return response()->json(['message' => 'Distribution already ended',], 400);
        }

        $distribution->ended_at = now();
        $distribution->save();

        return response()->json(['message' => 'Distribution ended']);
    }
}
