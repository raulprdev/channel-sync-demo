<?php

namespace Tests\Feature;

use App\Enums\ReservationStatus;
use App\Models\Channel;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class AirbnbWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Channel::factory()->create([
            'code' => 'airbnb',
            'name' => 'Airbnb',
        ]);
    }

    public function test_creates_reservation_from_webhook(): void
    {
        $payload = $this->airbnbPayload([
            'confirmation_code' => 'AIRBNB123',
            'listing_id' => 'prop_001',
            'guest_first_name' => 'John',
            'guest_last_name' => 'Doe',
            'guest_email' => 'john@example.com',
            'check_in' => '2026-04-01',
            'check_out' => '2026-04-05',
            'number_of_guests' => 2,
            'total_amount' => 500.00,
        ]);

        $response = $this->postJson(route('webhooks.airbnb'), $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'confirmed',
            ]);

        $this->assertDatabaseHas(Reservation::class, [
            'external_id' => 'AIRBNB123',
            'guest_name' => 'John Doe',
            'guest_email' => 'john@example.com',
            'check_in' => '2026-04-01',
            'check_out' => '2026-04-05',
            'guests' => 2,
            'total_amount' => 500.00,
            'status' => 'confirmed',
        ]);
    }

    public function test_creates_property_if_not_exists(): void
    {
        $payload = $this->airbnbPayload([
            'listing_id' => 'new_property_123',
        ]);

        $this->postJson(route('webhooks.airbnb'), $payload);

        $this->assertDatabaseHas(Property::class, [
            'external_id' => 'new_property_123',
        ]);
    }

    public function test_detects_conflict_with_overlapping_reservation(): void
    {
        $channel = Channel::where('code', 'airbnb')->first();
        $property = Property::factory()->create([
            'channel_id' => $channel->id,
            'external_id' => 'prop_001',
        ]);

        Reservation::factory()->create([
            'channel_id' => $channel->id,
            'property_id' => $property->id,
            'check_in' => '2026-04-03',
            'check_out' => '2026-04-08',
            'status' => ReservationStatus::Confirmed,
        ]);

        $payload = $this->airbnbPayload([
            'listing_id' => 'prop_001',
            'check_in' => '2026-04-01',
            'check_out' => '2026-04-05',
        ]);

        $response = $this->postJson(route('webhooks.airbnb'), $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'conflict',
            ]);
    }

    public function test_marks_existing_reservation_as_conflict(): void
    {
        $channel = Channel::where('code', 'airbnb')->first();
        $property = Property::factory()->create([
            'channel_id' => $channel->id,
            'external_id' => 'prop_001',
        ]);

        $existing = Reservation::factory()->create([
            'channel_id' => $channel->id,
            'property_id' => $property->id,
            'external_id' => 'EXISTING123',
            'check_in' => '2026-04-03',
            'check_out' => '2026-04-08',
            'status' => ReservationStatus::Confirmed,
        ]);

        $payload = $this->airbnbPayload([
            'listing_id' => 'prop_001',
            'check_in' => '2026-04-01',
            'check_out' => '2026-04-05',
        ]);

        $this->postJson(route('webhooks.airbnb'), $payload);

        $this->assertDatabaseHas(Reservation::class, [
            'id' => $existing->id,
            'external_id' => 'EXISTING123',
            'status' => 'conflict',
        ]);
    }

    public function test_returns_existing_reservation_if_already_processed(): void
    {
        $payload = $this->airbnbPayload([
            'confirmation_code' => 'DUPLICATE123',
        ]);

        $this->postJson(route('webhooks.airbnb'), $payload);
        $response = $this->postJson(route('webhooks.airbnb'), $payload);

        $response->assertOk();
        $this->assertDatabaseCount(Reservation::class, 1);
    }

    public function test_stores_raw_payload(): void
    {
        $payload = $this->airbnbPayload([
            'confirmation_code' => 'RAW123',
        ]);

        $this->postJson(route('webhooks.airbnb'), $payload);

        $reservation = Reservation::where('external_id', 'RAW123')->first();
        $this->assertEquals('RAW123', $reservation->raw_payload['confirmation_code']);
    }

    protected function airbnbPayload(array $overrides = []): array
    {
        return [
            'confirmation_code' => Arr::get($overrides, 'confirmation_code', 'DEFAULT123'),
            'listing_id' => Arr::get($overrides, 'listing_id', 'default_prop'),
            'guest' => [
                'first_name' => Arr::get($overrides, 'guest_first_name', 'Test'),
                'last_name' => Arr::get($overrides, 'guest_last_name', 'Guest'),
                'email' => Arr::get($overrides, 'guest_email', 'test@example.com'),
            ],
            'check_in' => Arr::get($overrides, 'check_in', '2026-05-01'),
            'check_out' => Arr::get($overrides, 'check_out', '2026-05-05'),
            'number_of_guests' => Arr::get($overrides, 'number_of_guests', 1),
            'total_price' => [
                'amount' => Arr::get($overrides, 'total_amount', 100.00),
                'currency' => 'USD',
            ],
        ];
    }
}