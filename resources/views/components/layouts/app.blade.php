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

    <main class="max-w-7xl mx-auto px-4 py-6">
        {{ $slot }}
    </main>
</body>
</html>