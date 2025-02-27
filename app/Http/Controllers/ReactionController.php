<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Http\Resources\ReactionResource;
use App\Models\Reaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ReactionResource::collection(Reaction::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionRequest $request): ReactionResource
    {
        return new ReactionResource(Reaction::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Reaction $reaction): ReactionResource
    {
        return new ReactionResource($reaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionRequest $request, Reaction $reaction): ReactionResource
    {
        $reaction->update($request->all());

        return new ReactionResource($reaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reaction $reaction): JsonResponse
    {
        $reaction->delete();

        return response()->json(['message' => 'Reaction has been removed']);
    }
}
