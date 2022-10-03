<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonCreateTest extends TestCase
{
    /**
     * Test successful create
     * 
     * @return void
     */
    public function test_successful_create()
    {
        $person = [
            'id' => fake()->name(),
            'topics' => ['books', 'magic', 'movies'],
        ];

        $response = $this->postJson('/api/people', $person);
        
        $response
            ->assertStatus(201)
            ->assertJson($person);
    }

    /**
     * Test avoid duplicate
     * 
     * @return void
     */
    public function test_avoid_duplicate()
    {
        $person = [
            'id' => fake()->name(),
            'topics' => ['books', 'magic', 'movies'],
        ];

        $this->postJson('/api/people', $person);
        $response = $this->postJson('/api/people', $person);
        
        $response->assertStatus(422);
    }
}
