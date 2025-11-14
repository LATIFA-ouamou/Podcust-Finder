<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
   //  token pour l'auth
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



     public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);


    }




}
