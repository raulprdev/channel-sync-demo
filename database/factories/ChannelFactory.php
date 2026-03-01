<?php

namespace Database\Factories;

use App\Enums\ChannelCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    public function definition(): array
    {
        $code = fake()->randomElement(ChannelCode::cases());

        return [
            'code' => $code,
            'name' => $code->name(),
            'is_active' => true,
        ];
    }

    public function airbnb(): static
    {
        return $this->state([
            'code' => ChannelCode::Airbnb,
            'name' => ChannelCode::Airbnb->name(),
        ]);
    }

    public function vrbo(): static
    {
        return $this->state([
            'code' => ChannelCode::Vrbo,
            'name' => ChannelCode::Vrbo->name(),
        ]);
    }

    public function booking(): static
    {
        return $this->state([
            'code' => ChannelCode::Booking,
            'name' => ChannelCode::Booking->name(),
        ]);
    }
}