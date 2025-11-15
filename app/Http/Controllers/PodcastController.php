<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;

class PodcastController extends Controller

/**
 * @OA\Tag(
 *     name="Podcasts",
 *     description="Gestion des podcasts (admin et annimateur)"
 * )
 */

{
    

    /**
 * @OA\Get(
 *     path="/api/podcasts",
 *     tags={"Podcasts"},
 *     summary="Lister tous les podcasts",
 *     @OA\Response(
 *         response=200,
 *         description="Liste des podcasts",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Podcast"))
 *     )
 * )
 */

    public function index()
    {
        return response()->json(Podcast::all());
    }




    /**
 * @OA\Post(
 *     path="/api/podcasts",
 *     tags={"Podcasts"},
 *     summary="Créer un podcast (admin ou animateur)",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title","description"},
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="description", type="string"),
 *                 @OA\Property(property="image", type="string", format="binary")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Podcast créé",
 *         @OA\JsonContent(ref="#/components/schemas/Podcast")
 *     ),
 *     @OA\Response(response=403, description="Non autorisé")
 * )
 */

  
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

    /**
 * @OA\Get(
 *     path="/api/podcasts/{id}",
 *     tags={"Podcasts"},
 *     summary="Afficher un podcast",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du podcast",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Podcast trouvé",
 *         @OA\JsonContent(ref="#/components/schemas/Podcast")
 *     ),
 *     @OA\Response(response=404, description="Podcast non trouvé")
 * )
 */

   
    public function show(Podcast $podcast)
    {
        return response()->json($podcast);
    }

   
/**
 * @OA\Put(
 *     path="/api/podcasts/{id}",
 *     tags={"Podcasts"},
 *     summary="Modifier un podcast (admin ou animateur propriétaire)",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du podcast",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="description", type="string"),
 *                 @OA\Property(property="image", type="string", format="binary")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Podcast mis à jour"),
 *     @OA\Response(response=403, description="Accès refusé")
 * )
 */


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

   


    /**
 * @OA\Delete(
 *     path="/api/podcasts/{id}",
 *     tags={"Podcasts"},
 *     summary="Supprimer un podcast (admin ou animateur propriétaire)",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Podcast supprimé avec succès"),
 *     @OA\Response(response=403, description="Non autorisé")
 * )
 */

    public function destroy(Podcast $podcast)
    {
         $this->authorize('delete', $podcast);
        $podcast->delete();

        return response()->json(['message' => 'Podcast supprimé avec succès']);


    }
}
