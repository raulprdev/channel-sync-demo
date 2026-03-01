@props(['reservation'])

@php
    $isConflict = $reservation->status->isConflict();
    $rowClass = $isConflict ? 'bg-red-50 border-l-4 border-red-500' : 'bg-white';
@endphp

<div class="{{ $rowClass }} px-4 py-3 flex items-center justify-between">
    <div class="flex items-center space-x-4">
        <span class="text-sm text-gray-500">{{ $reservation->created_at->format('H:i:s') }}</span>

        @if($isConflict)
            <span class="text-red-600 font-medium">CONFLICT</span>
        @else
            <span class="text-green-600">New</span>
        @endif

        <span class="text-gray-900">{{ $reservation->property->name }}</span>

        <x-channel-badge :channel="$reservation->channel" />
    </div>

    <div class="text-sm text-gray-500">
        {{ $reservation->guest_name }} &middot;
        {{ $reservation->check_in->format('M j') }} - {{ $reservation->check_out->format('M j') }}
    </div>
</div>