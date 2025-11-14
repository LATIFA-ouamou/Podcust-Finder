<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Podcast;

class EpisodeController extends Controller
{
    
    public function index()
    {
        
        $episodes = Episode::with('podcast')->get();

        return response()->json($episodes);
    }

  
public function store(StoreEpisodeRequest $request, Podcast $podcast)
{
    try {
     $this->authorize('create', Episode::class);

        $episode = $podcast->episodes()->create($request->validated());
        $episode->load('podcast');
        return response()->json([
            'message' => 'Épisode créé avec succès',
            'episode' => $episode
        ], 201);

    } catch (\Exception $th) {
       
        return response()->json([
            'error' => $th->getMessage()
        ], 500);
    }
}


    
    public function show(Episode $episode)

    {

        $episode->load('podcast');

        return response()->json($episode);
    }

    
    public function update(UpdateEpisodeRequest $request, Episode $episode)
    { $this->authorize('update', $episode);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('episodes', 'public');
            $data['image'] = $path;
        }

        $episode->update($data);

        return response()->json([
            'message' => 'Épisode mis à jour avec succès',
            'episode' => $episode,
        ]);
    }

    
    public function destroy(Episode $episode)
    {  $this->authorize('delete', $episode);
        $episode->delete();

        return response()->json(['message' => 'Épisode supprimé avec succès']);
    }



    public function listByPodcast(Podcast $podcast)
{
    return response()->json(
        $podcast->episodes()->with('podcast')->get()
    );
}

}
