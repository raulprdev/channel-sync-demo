<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'external_id' => 'PROP-' . fake()->unique()->numberBetween(1000, 9999),
            'channel_id' => Channel::factory(),
            'name' => fake()->streetAddress(),
            'address' => fake()->address(),
        ];
    }
}