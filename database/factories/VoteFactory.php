<?php

namespace Database\Factories;

use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        $poll = Poll::inRandomOrder()
            ->limit(1)
            ->get();

        $options = Arr::random(
            $poll[0]->options()
                ->get()
                ->toArray()
        );

        return [
            'poll_id' => $options['poll_id'],
            'poll_option_id' => $options['id'],
            'voter_ip' => fake()->ipv4()
        ];
    }
}
