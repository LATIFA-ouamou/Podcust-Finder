<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{



    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentification"},
     *     summary="Créer un nouveau compte utilisateur",
     *     description="Inscription d'un nouvel utilisateur et génération d'un token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="Sara"),
     *             @OA\Property(property="email", type="string", example="sara@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="role", type="string", example="user")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Inscription réussie"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function register(Request $request){
        $validated=$request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required', 
        ]);


    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' =>  $validated['role'],

    ]);
 
    $token = $user->createToken($user->name)->plainTextToken;
        
    return response()->json([
        'message' => 'Inscription réussie',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
          
        ],
        'token' => $token
    ], 201);



    }



    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentification"},
     *     summary="Connexion utilisateur",
     *     description="Vérifie les identifiants et renvoie un token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="sara@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants incorrects"
     *     )
     * )
     */
public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);

        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'role' => $user->role,
            'token' => $token,
            
        ]);
    }


public function reset(ResetPasswordRequest $request)
{
    try {
        $infos = $request->validated();

        $user = User::where('email', $infos['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Aucun utilisateur trouvé avec cet email.'
            ], 404);
        }

        $user->update([
            'password' => Hash::make($infos['password'])
        ]);

        return response()->json([
            'message' => 'Votre mot de passe est réinitialisé avec succès.'
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}






/**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentification"},
     *     summary="Déconnexion de l'utilisateur",
     *     security={{ "sanctum": {} }},
     *     description="Supprime le token actif",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Déconnecté avec succès"
     *     )
     * )
     */
     public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);


    }



public function hello(){
    return "helo";

}



function test1(){
    return "hi";
}





}
