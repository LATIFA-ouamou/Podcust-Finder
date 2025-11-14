<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;

class PodcastController extends Controller
{
    
    public function index()
    {
        return response()->json(Podcast::all());
    }

  
    public function store(StorePodcastRequest $request)
    {
        $this->authorize('create',Podcast::class);
        
        $data = $request->validated();

        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('podcasts', 'public');
            $data['image'] = $path;
        } 

        $podcast = $request->user()->podcasts()->create($data);

        return response()->json([
            'message' => 'Podcast créé avec succès',
            'podcast' => $podcast
        ], 201);
    }

   
    public function show(Podcast $podcast)
    {
        return response()->json($podcast);
    }

   
    public function update(UpdatePodcastRequest $request, Podcast $podcast)
    {
         $this->authorize('update', $podcast);
        $data = $request->validated();

       
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('podcasts', 'public');
            $data['image'] = $path;
        }

        $podcast->update($data);

        return response()->json([
            'message' => 'Podcast mis à jour avec succès',
            'podcast' => $podcast,
        ]);
    }

   


    public function destroy(Podcast $podcast)
    {
         $this->authorize('delete', $podcast);
        $podcast->delete();

        return response()->json(['message' => 'Podcast supprimé avec succès']);


    }
}
