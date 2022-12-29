<?php

namespace Tests\Unit;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteControllerTest extends TestCase
{
    use RefreshDatabase;

    const BASE_URL = '/api/v1';

    public function test_vote_request_with_empty_body_fails_validation()
    {
        $response = $this->postJson(self::BASE_URL . '/vote');

        $response
            ->assertStatus(400)
            ->assertJson(['errors' => true]);
    }

    public function test_vote_request_returns_error_if_poll_or_option_is_invalid()
    {
        $poll = Poll::factory()
            ->has(PollOption::factory()->count(2), 'options')
            ->create();

        $option = $poll->options->first();

        $poll->delete();

        $response = $this->postJson(self::BASE_URL . '/vote', [
            'poll' => $poll->id,
            'option' => $option->id
        ]);

        $response
            ->assertNotFound()
            ->assertJson(['message' => 'Poll not found']);
    }

    public function test_vote_request_to_expired_poll_returns_error()
    {
        $poll = Poll::create([
            'title' => fake()->sentence(),
            'email' => fake()->safeEmail(),
            'expires_at' => '1999-12-31 12:00:00'
        ]);

        $poll->options()->saveMany([
            new PollOption(['option' => 'Option 1']),
            new PollOption(['option' => 'Option 2'])
        ]);

        $response = $this->postJson(self::BASE_URL . '/vote', [
            'poll' => $poll->id,
            'option' => $poll->options[0]->id
        ]);

        $response
            ->assertForbidden()
            ->assertJson(['message' => 'Poll is expired']);
    }

    public function test_one_ip_cannot_vote_twice_on_same_poll()
    {
        $poll = Poll::factory()
            ->has(PollOption::factory()->count(2), 'options')
            ->create();

        $option = $poll->options->first();

        $this->postJson(self::BASE_URL . '/vote', [
            'poll' => $poll->id,
            'option' => $option->id
        ]);

        $response = $this->postJson(self::BASE_URL . '/vote', [
            'poll' => $poll->id,
            'option' => $option->id
        ]);

        $response
            ->assertForbidden()
            ->assertJson(['message' => 'User already voted on this poll']);
    }

    public function test_vote_request_is_registered_correctly()
    {
        $poll = Poll::factory()
            ->has(PollOption::factory()->count(2), 'options')
            ->create();

        $option = $poll->options->first();

        $response = $this->postJson(self::BASE_URL . '/vote', [
            'poll' => $poll->id,
            'option' => $option->id
        ]);

        $response
            ->assertCreated()
            ->assertJson(['message' => 'Vote registered successfully']);
    }
}
