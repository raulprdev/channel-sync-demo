<?php

namespace Tests\Feature;

use App\Enums\ReservationStatus;
use App\Models\Channel;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class VrboWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Channel::factory()->create([
            'code' => 'vrbo',
            'name' => 'VRBO',
        ]);
    }

    public function test_creates_reservation_from_webhook(): void
    {
        $payload = $this->vrboPayload([
            'confirmationId' => 'VRBO456',
            'propertyId' => 'prop_001',
            'traveler_name' => 'Jane Smith',
            'traveler_email' => 'jane@example.com',
            'arrival' => '2026-04-10',
            'departure' => '2026-04-15',
            'guestCount' => 3,
            'totalAmount' => 750.00,
        ]);

        $response = $this->postJson(route('webhooks.vrbo'), $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'confirmed',
            ]);

        $this->assertDatabaseHas(Reservation::class, [
            'external_id' => 'VRBO456',
            'guest_name' => 'Jane Smith',
            'guest_email' => 'jane@example.com',
            'check_in' => '2026-04-10',
            'check_out' => '2026-04-15',
            'guests' => 3,
            'total_amount' => 750.00,
            'status' => 'confirmed',
        ]);
    }

    public function test_detects_conflict_with_overlapping_reservation(): void
    {
        $channel = Channel::where('code', 'vrbo')->first();
        $property = Property::factory()->create([
            'channel_id' => $channel->id,
            'external_id' => 'prop_001',
        ]);

        Reservation::factory()->create([
            'channel_id' => $channel->id,
            'property_id' => $property->id,
            'check_in' => '2026-04-12',
            'check_out' => '2026-04-18',
            'status' => ReservationStatus::Confirmed,
        ]);

        $payload = $this->vrboPayload([
            'propertyId' => 'prop_001',
            'arrival' => '2026-04-10',
            'departure' => '2026-04-15',
        ]);

        $response = $this->postJson(route('webhooks.vrbo'), $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'conflict',
            ]);
    }

    protected function vrboPayload(array $overrides = []): array
    {
        return [
            'confirmationId' => Arr::get($overrides, 'confirmationId', 'DEFAULT456'),
            'propertyId' => Arr::get($overrides, 'propertyId', 'default_prop'),
            'traveler' => [
                'name' => Arr::get($overrides, 'traveler_name', 'Test Guest'),
                'email' => Arr::get($overrides, 'traveler_email', 'test@example.com'),
            ],
            'arrival' => Arr::get($overrides, 'arrival', '2026-05-01'),
            'departure' => Arr::get($overrides, 'departure', '2026-05-05'),
            'guestCount' => Arr::get($overrides, 'guestCount', 1),
            'totalAmount' => Arr::get($overrides, 'totalAmount', 100.00),
        ];
    }
}