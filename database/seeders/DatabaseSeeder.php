<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Podcast;
use App\Models\Episode;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée 5 users
        User::factory(5)->create()->each(function ($user) {
            // Pour chaque user 3 podcasts
            Podcast::factory(3)->create([
                'user_id' => $user->id
            ])->each(function ($podcast) {
                // Pour chaque podcast, créer 2 épisodes
                Episode::factory(2)->create([
                    'podcast_id' => $podcast->id
                ]);
            });
        });
    }
}
