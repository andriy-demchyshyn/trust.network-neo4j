<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShortestPathTest extends TestCase
{
    /**
     * Test successful find
     * 
     * @return void
     */
    public function test_successful_find()
    {
        $person_name = fake()->name();
        $friend_name1 = fake()->name();
        $friend_name2 = fake()->name();
        $friend_name3 = fake()->name();
        $friend_name4 = fake()->name();
        $friend_name5 = fake()->name();

        $person = [
            'id' => $person_name,
            'topics' => ['books', 'magic', 'movies'],
        ];
        $friend1 = [
            'id' => $friend_name1,
            'topics' => ['books', 'magic'],
        ];
        $friend2 = [
            'id' => $friend_name2,
            'topics' => ['books', 'magic'],
        ];
        $friend3 = [
            'id' => $friend_name3,
            'topics' => ['books', 'magic'],
        ];
        $friend4 = [
            'id' => $friend_name4,
            'topics' => ['books', 'magic'],
        ];
        $friend5 = [
            'id' => $friend_name5,
            'topics' => ['books', 'magic'],
        ];

        $this->postJson('/api/people', $person);
        $this->postJson('/api/people', $friend1);
        $this->postJson('/api/people', $friend2);
        $this->postJson('/api/people', $friend3);
        $this->postJson('/api/people', $friend4);
        $this->postJson('/api/people', $friend5);

        $response = $this->postJson('/api/people/'.$person_name.'/trust_connections', [
            $friend_name1 => 8,
            $friend_name2 => 6,
        ]);

        $response = $this->postJson('/api/people/'.$friend_name1.'/trust_connections', [
            $friend_name3 => 8,
            $friend_name4 => 9,
        ]);

        $response = $this->postJson('/api/people/'.$friend_name2.'/trust_connections', [
            $friend_name5 => 7,
            $friend_name3 => 5,
        ]);

        $response = $this->postJson('/api/people/'.$friend_name3.'/trust_connections', [
            $friend_name5 => 7,
            $friend_name4 => 5,
        ]);

        $message = [
            'text' => fake()->sentence(),
            'topics' => ['books', 'magic'],
            'from_person_id' => $person_name,
            'min_trust_level' => 5,
        ];
        
        $response = $this->postJson('/api/path', $message);
        
        $response->assertStatus(201);
    }
}
