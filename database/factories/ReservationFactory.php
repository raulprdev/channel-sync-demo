<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Channel;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('+1 day', '+30 days');
        $checkOut = (clone $checkIn)->modify('+' . fake()->numberBetween(2, 7) . ' days');

        return [
            'external_id' => 'RES-' . fake()->unique()->numberBetween(10000, 99999),
            'channel_id' => Channel::factory(),
            'property_id' => Property::factory(),
            'guest_name' => fake()->name(),
            'guest_email' => fake()->safeEmail(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => fake()->numberBetween(1, 6),
            'total_amount' => fake()->randomFloat(2, 100, 1000),
            'status' => ReservationStatus::Confirmed,
            'raw_payload' => [],
        ];
    }

    public function conflict(): static
    {
        return $this->state(['status' => ReservationStatus::Conflict]);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => ReservationStatus::Cancelled]);
    }
}