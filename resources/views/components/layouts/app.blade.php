<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Channel Sync Demo' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">Channel Sync Demo</h1>
        </div>
    </header>

    <div class="bg-indigo-50 border-b border-indigo-100">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <p class="text-sm text-indigo-700">
                🔬 This is an experiment in OTA integration patterns.
            </p>
            <div class="flex items-center space-x-4 text-sm">
                <a href="https://raulpr.dev/articles/ota-webhook-integration-yet-another-laravel-experiment" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    Read the article &rarr;
                </a>
                <a href="https://github.com/tanohb/channel-sync-demo" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    View source &rarr;
                </a>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-6">
        {{ $slot }}
    </main>
</body>
</html>