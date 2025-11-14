<?php

namespace App\Policies;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PodcastPolicy
{
    /**
     * Qui peut créer un podcast ?
     */
    public function create(User $user): Response
    {
        return in_array($user->role, ['admin', 'annimateur'])
            ? Response::allow()
            : Response::deny("Vous n'avez pas la permission de créer un podcast.");
    }

    /**
     * Qui peut mettre à jour un podcast ?
     */
    public function update(User $user, Podcast $podcast): Response
    {
        
        if ($user->role === 'admin') {
            return Response::allow();
        }

      
        if ($user->role === 'annimateur' && $podcast->user_id == $user->id) {
            return Response::allow();
        }

       
        return Response::deny("Vous n'avez pas la permission de mettre à jour ce podcast.");
    }

    /**
     * Qui peut supprimer un podcast ?
     */
    public function delete(User $user, Podcast $podcast): Response
    {
        
        if ($user->role === 'admin') {
            return Response::allow();
        }

        
        if ($user->role === 'annimateur' && $podcast->user_id == $user->id) {
            return Response::allow();
        }

        return Response::deny("Vous n'avez pas la permission de supprimer ce podcast.");
    }
}
