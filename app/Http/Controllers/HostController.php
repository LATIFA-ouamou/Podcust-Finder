<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HostController extends Controller


/**
 * @OA\Tag(
 *     name="Hosts",
 *     description="Gestion des annimateurs"
 * )
 */

{
    /**
 * @OA\Get(
 *     path="/api/hosts",
 *     tags={"Hosts"},
 *     summary="Lister tous les animateurs",
 *     @OA\Response(
 *         response=200,
 *         description="Liste des animateurs",
 *         @OA\JsonContent(type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="role", type="string", example="animateur")
 *             )
 *         )
 *     )
 * )
 */

    
    public function index()
    {
        $hosts = User::where('role', 'animateur')->get();
        return response()->json($hosts);
    }

   

/**
 * @OA\Get(
 *     path="/api/hosts/{id}",
 *     tags={"Hosts"},
 *     summary="Afficher un animateur",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'animateur",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails de l'animateur",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="role", type="string", example="animateur")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cet utilisateur n’est pas un animateur"
 *     )
 * )
 */


    public function show(User $host)
    {
        if ($host->role !== 'animateur') {
            return response()->json(['message' => 'Cet utilisateur n’est pas un animateur'], 404);
        }
        return response()->json($host);
    }




    /**
 * @OA\Post(
 *     path="/api/hosts",
 *     tags={"Hosts"},
 *     summary="Créer un nouvel animateur",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Animateur créé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="host", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation"
 *     )
 * )
 */

  
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $host = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'animateur', 
        ]);

        return response()->json([
            'message' => 'Animateur créé avec succès',
            'host' => $host
        ], 201);
    }

   



    /**
 * @OA\Put(
 *     path="/api/hosts/{id}",
 *     tags={"Hosts"},
 *     summary="Mettre à jour un animateur",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'animateur",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", nullable=true),
 *             @OA\Property(property="email", type="string", nullable=true),
 *             @OA\Property(property="password", type="string", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Animateur mis à jour avec succès"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cet utilisateur n’est pas un animateur"
 *     )
 * )
 */

    public function update(Request $request, User $host)
    {
        if ($host->role !== 'animateur') {
            return response()->json(['message' => 'Cet utilisateur n’est pas un animateur'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $host->id,
            'password' => 'sometimes|string|min:6',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $host->update($request->all());

        return response()->json([
            'message' => 'Animateur mis à jour avec succès',
            'host' => $host
        ]);
    }

    

/**
 * @OA\Delete(
 *     path="/api/hosts/{id}",
 *     tags={"Hosts"},
 *     summary="Supprimer un animateur",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'animateur",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Animateur supprimé avec succès"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cet utilisateur n’est pas un animateur"
 *     )
 * )
 */


    public function destroy(User $host)
    {
        if ($host->role !== 'animateur') {
            return response()->json(['message' => 'Cet utilisateur n’est pas un animateur'], 404);
        }

        $host->delete();

        return response()->json(['message' => 'Animateur supprimé avec succès']);
    }
}
