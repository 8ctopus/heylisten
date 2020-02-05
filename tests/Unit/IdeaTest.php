<?php

namespace Tests\Unit;

use Tests\TestCase;

class IdeaTest extends TestCase
{
    public function testCanCreateAnIdea()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post(route('ideas.store'), $data)
//            ->dump()
            ->assertStatus(201)
            ->assertJson($data);
    }

//    public function testCanShowAnIdea() {
//
//    }
}
