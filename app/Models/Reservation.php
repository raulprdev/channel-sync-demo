<?php

namespace App\Models;

use App\DataTransferObjects\ReservationData;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    public static function fromReservationData(
        ReservationData $data,
        int $channelId,
        int $propertyId,
        ReservationStatus $status
    ): self {
        return new self([
            'external_id' => $data->externalId,
            'channel_id' => $channelId,
            'property_id' => $propertyId,
            'guest_name' => $data->guestName,
            'guest_email' => $data->guestEmail,
            'check_in' => $data->checkIn,
            'check_out' => $data->checkOut,
            'guests' => $data->guests,
            'total_amount' => $data->totalAmount,
            'status' => $status,
            'raw_payload' => $data->rawPayload,
        ]);
    }

    protected $fillable = [
        'external_id',
        'channel_id',
        'property_id',
        'guest_name',
        'guest_email',
        'check_in',
        'check_out',
        'guests',
        'total_amount',
        'status',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'total_amount' => 'decimal:2',
            'raw_payload' => 'array',
            'status' => ReservationStatus::class,
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}