<?php

namespace Tests\Unit;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PollTest extends TestCase
{
    use DatabaseTransactions;

    const BASE_URL = '/api/v1';

    public function test_request_to_index_method_returns_poll_collection()
    {
        $response = $this->getJson(self::BASE_URL . '/polls');

        $response
            ->assertSuccessful()
            ->assertJson(['data' => true]);
    }

    public function test_request_to_show_method_returns_a_poll_and_options()
    {
        $poll = Poll::factory()
            ->has(PollOption::factory()
                ->count(fake()->randomNumber(1, 6)), 'options')
            ->create();

        $response = $this->getJson(self::BASE_URL . '/polls/' . $poll->id);

        $expectedStructure = [
            'data' => [
                'id',
                'title',
                'options',
                'total_votes'
            ]
        ];

        $response
            ->assertSuccessful()
            ->assertJsonStructure($expectedStructure);
    }

    public function test_request_to_store_method_creates_a_new_poll()
    {
        $payload = [
            'title' => 'Example poll',
            'options' => [
                'Example option 1',
                'Example option 2'
            ],
            'email' => 'angelamoss@allsafe.com',
            'expires_at' => '2026-01-01 12:00'
        ];

        $response = $this->postJson(self::BASE_URL . '/polls', $payload);

        $expectedStructure = [
            'data' => [
                'id',
                'title',
                'options',
                'total_votes'
            ]
        ];

        $response
            ->assertSuccessful()
            ->assertJsonStructure($expectedStructure);
    }

    public function test_request_to_destroy_method_removes_a_poll()
    {
        $poll = Poll::factory()
            ->has(PollOption::factory()
                ->count(fake()->randomNumber(1, 6)), 'options')
            ->create();

        $response = $this->delete(self::BASE_URL . '/polls/' . $poll->id);

        $response
            ->assertSuccessful()
            ->assertJson(['message' => 'Poll deleted successfully']);
    }
}
