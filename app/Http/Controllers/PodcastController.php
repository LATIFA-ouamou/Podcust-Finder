<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;

class PodcastController extends Controller
{
    /**
     * Lister tous les podcasts
     */
    public function index()
    {
        return Podcast::with('user')->latest()->get();
    }

    /**
     * Créer un nouveau podcast
     */
    public function store(StorePodcastRequest $request)
    {
        $user = Auth::user();

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('podcasts', 'public')
            : null;

        $podcast = Podcast::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Podcast créé avec succès',
            'podcast' => $podcast
        ], 201);
    }

    /**
     * Mettre à jour un podcast existant
     */
    public function update(UpdatePodcastRequest $request, Podcast $podcast)
    {
        $user = Auth::user();

        if ($user->id !== $podcast->user_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        // Si une nouvelle image est envoyée
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('podcasts', 'public');
            $podcast->image = $imagePath;
        }

        // Met à jour uniquement les champs envoyés
        $podcast->update($request->only(['title', 'description']));

        return response()->json([
            'message' => 'Podcast mis à jour avec succès',
            'podcast' => $podcast
        ]);
    }

    /**
     * Supprimer un podcast
     */
    public function destroy(Podcast $podcast)
    {
        $user = Auth::user();

        if ($user->id !== $podcast->user_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $podcast->delete();

        return response()->json(['message' => 'Podcast supprimé avec succès']);
    }
}
