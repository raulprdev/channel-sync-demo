<div class="divide-y divide-gray-200">
    @forelse($reservations as $reservation)
        <x-reservation-row :reservation="$reservation" />
    @empty
        <div class="px-4 py-8 text-center text-gray-500">
            No reservations yet. Webhooks will appear here.
        </div>
    @endforelse
</div>

@if($reservations->hasPages())
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $reservations->links() }}
    </div>
@endif