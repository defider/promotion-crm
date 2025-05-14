<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Models\Distribution;
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
    public function update(UpdateApartmentRequest $request, Apartment $apartment): JsonResponse
    {
        $apartment->update($request->validated());

        return response()->json([
            'message' => 'Apartment updated',
            'data' => new ApartmentResource($apartment),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment): JsonResponse
    {
        $apartment->delete();

        return response()->json(['message' => 'Apartment deleted']);
    }

    public function react(UpdateApartmentRequest $request, Apartment $apartment): JsonResponse
    {
        $activeDistribution = Distribution::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if (! $activeDistribution || $apartment->building_id !== $activeDistribution->building_id) {
            return response()->json([
                'message' => "You can't react to this apartment. It doesn't belong to your current distribution",
            ], 403);
        }

        $reactionId = $request->input('reaction_id');

        $apartment->update([
            'reaction_id' => $reactionId,
            'reaction_time' => now(),
        ]);

        return response()->json([
            'message' => 'Reaction updated',
            'data' => new ApartmentResource($apartment),
        ]);
    }
}
