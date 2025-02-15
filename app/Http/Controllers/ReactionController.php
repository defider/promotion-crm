<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Models\Reaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class ReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Reaction::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionRequest $request)
    {
        return Reaction::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Reaction $reaction): Reaction
    {
        return $reaction;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionRequest $request, Reaction $reaction): Reaction
    {
        $reaction->update($request->all());

        return $reaction;
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
