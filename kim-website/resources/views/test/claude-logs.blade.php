<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claude Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('test.claude.config') }}" class="text-blue-500 hover:underline">← Back to Tests</a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">📋 Laravel Logs (Last 100 lines)</h1>
            <button onclick="location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                🔄 Refresh
            </button>
        </div>

        @if(!$error)
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-4">Last update: {{ $lastUpdate }}</p>
            <pre class="bg-gray-900 text-green-400 p-4 rounded text-xs overflow-x-auto" style="max-height: 600px; overflow-y: auto;">{{ $logs }}</pre>
        </div>
        @else
        <div class="bg-red-100 border-l-4 border-red-500 p-4">
            <p class="font-bold text-red-700">Error reading logs</p>
            <p class="text-sm">{{ $logs }}</p>
        </div>
        @endif

        <div class="mt-6 bg-yellow-100 border-l-4 border-yellow-500 p-4">
            <p class="font-bold">💡 Tips:</p>
            <ul class="list-disc list-inside mt-2 text-sm">
                <li>Look for lines starting with "[local.INFO]" or "[local.ERROR]"</li>
                <li>Search for "Claude" or "API" to find relevant entries</li>
                <li>Check the timestamp to see when errors occurred</li>
                <li>Full log file: storage/logs/laravel.log</li>
            </ul>
        </div>
    </div>
</body>
</html>