<?php

namespace Database\Factories;

use App\Models\PollOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PollOption>
 */
class PollOptionFactory extends Factory
{
    protected $model = PollOption::class;

    public function definition(): array
    {
        return [
            'option' => fake()->text(20)
        ];
    }
}
