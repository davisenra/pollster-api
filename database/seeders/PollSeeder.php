<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            Poll::factory()
                ->has(PollOption::factory()->count(fake()->randomNumber(1, 6)), 'options')
                ->create();
        }
    }
}
