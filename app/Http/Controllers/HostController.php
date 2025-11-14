<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HostController extends Controller
{
    
    public function index()
    {
        $hosts = User::where('role', 'animateur')->get();
        return response()->json($hosts);
    }

   
    public function show(User $host)
    {
        if ($host->role !== 'animateur') {
            return response()->json(['message' => 'Cet utilisateur n’est pas un animateur'], 404);
        }
        return response()->json($host);
    }

  
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
            'role' => 'animateur', // rôle fixe
        ]);

        return response()->json([
            'message' => 'Animateur créé avec succès',
            'host' => $host
        ], 201);
    }

   
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

    
    public function destroy(User $host)
    {
        if ($host->role !== 'animateur') {
            return response()->json(['message' => 'Cet utilisateur n’est pas un animateur'], 404);
        }

        $host->delete();

        return response()->json(['message' => 'Animateur supprimé avec succès']);
    }
}
