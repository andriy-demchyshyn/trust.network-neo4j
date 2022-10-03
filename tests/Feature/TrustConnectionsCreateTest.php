<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TrustConnectionsCreateTest extends TestCase
{
    /**
     * Test successful create
     * 
     * @return void
     */
    public function test_successful_create()
    {
        $person_name = fake()->name();
        $friend_name = fake()->name();

        $person = [
            'id' => $person_name,
            'topics' => ['books', 'magic', 'movies'],
        ];
        $friend = [
            'id' => $friend_name,
            'topics' => ['books', 'magic'],
        ];

        $this->postJson('/api/people', $person);
        $this->postJson('/api/people', $friend);

        $trust_connections = [
            $friend_name => 7
        ];

        $response = $this->postJson('/api/people/'.$person_name.'/trust_connections', $trust_connections);
        
        $response->assertStatus(201);
    }
}
