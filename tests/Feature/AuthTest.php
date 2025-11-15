<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
   
 use DatabaseMigrations;

 /** @test */
 public function user_can_register()
    {
        $payload = [
            'name' => 'Latifa',
            'email' => 'laatifa@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role'=>'user'
        ];

        $response = $this->postJson('/api/register', $payload);
// dd($response->json());
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'role'
            ],
            'token'
        ]);
    }




}
