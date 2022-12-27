<?php

namespace Database\Factories;

use App\Models\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Poll>
 */
class PollFactory extends Factory
{
    protected $model = Poll::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'email' => fake()->safeEmail(),
            'expires_at' => fake()->dateTimeInInterval('now', '+15 days')
        ];
    }
}
