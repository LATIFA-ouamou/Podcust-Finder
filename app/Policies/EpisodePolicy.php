<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpisodePolicy
{
    /**
     * Qui peut créer un épisode ?
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'animateur']);
    }

    /**
     * Qui peut mettre à jour un épisode ?
     */
    public function update(User $user, Episode $episode): Response
    {
        
        if ($user->role === 'admin') {
            return Response::allow();
        }

       
        if ($user->role === 'animateur' && $episode->podcast->user_id == $user->id) {
            return Response::allow();
        }

        return Response::deny("Vous n'avez pas la permission de mettre à jour cet épisode.");
    }

    /**
     * Qui peut supprimer un épisode ?
     */
    public function delete(User $user, Episode $episode): Response
    {
        
        if ($user->role === 'admin') {
            return Response::allow();
        }

       
        if ($user->role === 'animateur' && $episode->podcast->user_id == $user->id) {
            return Response::allow();
        }

        return Response::deny("Vous n'avez pas la permission de supprimer cet épisode.");
    }
}
