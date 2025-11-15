<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Podcast;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Episodes",
 *     description="Gestion des épisodes"
 * )
 */
class EpisodeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/episodes",
     *     tags={"Episodes"},
     *     summary="Liste de tous les épisodes",
     *     @OA\Response(
     *         response=200,
     *         description="Liste récupérée",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Episode"))
     *     )
     * )
     */
    public function index()
    {
        $episodes = Episode::with('podcast')->get();
        return response()->json($episodes);
    }

    /**
     * @OA\Post(
     *     path="/api/podcasts/{podcast}/episodes",
     *     tags={"Episodes"},
     *     summary="Créer un épisode pour un podcast",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="podcast",
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
     *                 required={"title","description"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="audio_file", type="string", format="binary", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Épisode créé",
     *         @OA\JsonContent(ref="#/components/schemas/Episode")
     *     )
     * )
     */
    public function store(StoreEpisodeRequest $request, Podcast $podcast)
    {
        try {
            $this->authorize('create', Episode::class);

            $data = $request->validated();

            if ($request->hasFile('audio_file')) {
                $path = $request->file('audio_file')->store('episodes/audio', 'public');
                $data['audio_file'] = $path;
            }

            $episode = $podcast->episodes()->create($data);
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

    /**
     * @OA\Get(
     *     path="/api/episodes/{id}",
     *     tags={"Episodes"},
     *     summary="Afficher un épisode",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'épisode",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Épisode trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/Episode")
     *     )
     * )
     */
    public function show(Episode $episode)
    {
        $episode->load('podcast');
        return response()->json($episode);
    }

    /**
     * @OA\Put(
     *     path="/api/episodes/{id}",
     *     tags={"Episodes"},
     *     summary="Mettre à jour un épisode",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'épisode",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="audio_file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Épisode mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Episode")
     *     )
     * )
     */
    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        $this->authorize('update', $episode);

        $data = $request->validated();

        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')->store('episodes/audio', 'public');
            $data['audio_file'] = $path;
        }

        $episode->update($data);

        return response()->json([
            'message' => 'Épisode mis à jour avec succès',
            'episode' => $episode,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/episodes/{id}",
     *     tags={"Episodes"},
     *     summary="Supprimer un épisode",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'épisode",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Épisode supprimé"
     *     )
     * )
     */
    public function destroy(Episode $episode)
    {
        $this->authorize('delete', $episode);

        $episode->delete();

        return response()->json(['message' => 'Épisode supprimé avec succès']);
    }

    /**
     * Liste des épisodes par podcast
     */
    public function listByPodcast(Podcast $podcast)
    {
        return response()->json(
            $podcast->episodes()->with('podcast')->get()
        );
    }
}
