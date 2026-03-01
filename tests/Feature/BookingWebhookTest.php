<?php

namespace Tests\Feature;

use App\Enums\ReservationStatus;
use App\Models\Channel;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class BookingWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Channel::factory()->create([
            'code' => 'booking',
            'name' => 'Booking.com',
        ]);
    }

    public function test_creates_reservation_from_webhook(): void
    {
        $payload = $this->bookingPayload([
            'reservation_id' => 'BOOKING789',
            'hotel_id' => 'prop_001',
            'guest_name' => 'Wilson, Bob',
            'guest_email' => 'bob@example.com',
            'checkin' => '15-04-2026',
            'checkout' => '20-04-2026',
            'adults' => 2,
            'total' => 900.00,
        ]);

        $response = $this->postJson(route('webhooks.booking'), $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'confirmed',
            ]);

        $this->assertDatabaseHas(Reservation::class, [
            'external_id' => 'BOOKING789',
            'guest_name' => 'Bob Wilson',
            'guest_email' => 'bob@example.com',
            'check_in' => '2026-04-15',
            'check_out' => '2026-04-20',
            'guests' => 2,
            'total_amount' => 900.00,
            'status' => 'confirmed',
        ]);
    }

    public function test_parses_guest_name_without_comma(): void
    {
        $payload = $this->bookingPayload([
            'guest_name' => 'John Doe',
        ]);

        $this->postJson(route('webhooks.booking'), $payload);

        $this->assertDatabaseHas(Reservation::class, [
            'guest_name' => 'John Doe',
        ]);
    }

    public function test_detects_conflict_with_overlapping_reservation(): void
    {
        $channel = Channel::where('code', 'booking')->first();
        $property = Property::factory()->create([
            'channel_id' => $channel->id,
            'external_id' => 'prop_001',
        ]);

        Reservation::factory()->create([
            'channel_id' => $channel->id,
            'property_id' => $property->id,
            'check_in' => '2026-04-17',
            'check_out' => '2026-04-22',
            'status' => ReservationStatus::Confirmed,
        ]);

        $payload = $this->bookingPayload([
            'hotel_id' => 'prop_001',
            'checkin' => '15-04-2026',
            'checkout' => '20-04-2026',
        ]);

        $response = $this->postJson(route('webhooks.booking'), $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'conflict',
            ]);
    }

    protected function bookingPayload(array $overrides = []): array
    {
        return [
            'reservation' => [
                'id' => Arr::get($overrides, 'reservation_id', 'DEFAULT789'),
                'hotel_id' => Arr::get($overrides, 'hotel_id', 'default_prop'),
                'guest_name' => Arr::get($overrides, 'guest_name', 'Guest, Test'),
                'guest_email' => Arr::get($overrides, 'guest_email', 'test@example.com'),
                'checkin' => Arr::get($overrides, 'checkin', '01-05-2026'),
                'checkout' => Arr::get($overrides, 'checkout', '05-05-2026'),
                'adults' => Arr::get($overrides, 'adults', 1),
            ],
            'price' => [
                'total' => Arr::get($overrides, 'total', 100.00),
                'currency' => 'EUR',
            ],
        ];
    }
}