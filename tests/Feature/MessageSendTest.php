<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageSendTest extends TestCase
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

        $message = [
            'text' => fake()->sentence(),
            'topics' => ['books', 'magic'],
            'from_person_id' => $person_name,
            'min_trust_level' => 5,
        ];
        
        $response = $this->postJson('/api/messages', $message);
        
        $response->assertStatus(201);
    }
}
