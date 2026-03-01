<x-layouts.app>
    <div class="space-y-6">
        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-stat-card label="Properties" :value="$stats['properties']" />
            <x-stat-card label="Reservations" :value="$stats['reservations']" color="green" />
            <x-stat-card label="Conflicts" :value="$stats['conflicts']" color="red" />
        </div>

        {{-- Channels --}}
        <div class="bg-white rounded-lg shadow px-4 py-3">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-500">Channels:</span>
                @foreach($channels as $channel)
                    <x-channel-badge :channel="$channel" />
                @endforeach
            </div>
        </div>

        {{-- Activity Log --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Activity Log</h2>
                <div class="flex items-center space-x-3">
                    <span id="status" class="text-sm text-gray-500"></span>
                    <button
                        id="refresh-btn"
                        onclick="refreshNow()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                    >
                        Refresh now
                    </button>
                </div>
            </div>

            <div id="activity-log">
                @include('partials.activity-log')
            </div>
        </div>
    </div>

    <script>
        let countdown = 30;
        let intervalId;

        function updateStatus(text) {
            document.getElementById('status').textContent = text;
        }

        function startCountdown() {
            countdown = 30;
            updateStatus('Updating in ' + countdown + 's');
            intervalId = setInterval(() => {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(intervalId);
                    refreshNow();
                } else {
                    updateStatus('Updating in ' + countdown + 's');
                }
            }, 1000);
        }

        function refreshNow() {
            clearInterval(intervalId);
            updateStatus('Updating...');
            document.getElementById('refresh-btn').disabled = true;

            fetch('{{ route('activity-log') }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('activity-log').innerHTML = html;
                    document.getElementById('refresh-btn').disabled = false;
                    startCountdown();
                })
                .catch(() => {
                    document.getElementById('refresh-btn').disabled = false;
                    startCountdown();
                });
        }

        startCountdown();
    </script>
</x-layouts.app>