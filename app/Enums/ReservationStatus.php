<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Conflict = 'conflict';

    public function isConflict(): bool
    {
        return $this === self::Conflict;
    }

    public function label(): string
    {
        return match ($this) {
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
            self::Conflict => 'Conflict',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Confirmed => 'green',
            self::Cancelled => 'gray',
            self::Conflict => 'red',
        };
    }
}