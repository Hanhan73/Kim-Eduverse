<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Simple Claude Call</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('test.claude.config') }}" class="text-blue-500 hover:underline">← Back to Config</a>
        </div>

        <h1 class="text-3xl font-bold mb-6">📡 Simple Claude API Call Test</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Test Prompt</h2>
            <pre class="bg-gray-100 p-4 rounded">{{ $prompt }}</pre>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Result</h2>
            
            @if($response['success'])
            <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-4">
                <p class="font-bold text-green-700">✅ SUCCESS!</p>
                <p class="text-sm text-gray-600">Duration: {{ $duration }} seconds</p>
            </div>

            <div class="bg-gray-100 p-4 rounded mb-4">
                <h3 class="font-bold mb-2">Claude Response:</h3>
                <p class="whitespace-pre-wrap">{{ $response['content'] }}</p>
            </div>

            @if(isset($response['usage']))
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-blue-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Input Tokens</p>
                    <p class="text-2xl font-bold">{{ $response['usage']['input_tokens'] }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Output Tokens</p>
                    <p class="text-2xl font-bold">{{ $response['usage']['output_tokens'] }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Model</p>
                    <p class="text-lg font-bold">{{ $response['model'] }}</p>
                </div>
            </div>
            @endif
            @else
            <div class="bg-red-100 border-l-4 border-red-500 p-4">
                <p class="font-bold text-red-700">❌ FAILED!</p>
                <p class="text-sm text-gray-600 mt-2">Error: {{ $response['error'] }}</p>
                @if(isset($response['error_message']))
                <p class="text-sm text-gray-600 mt-1">Message: {{ $response['error_message'] }}</p>
                @endif
            </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('test.claude.analysis') }}" class="bg-green-500 text-white p-4 rounded hover:bg-green-600 text-center">
                Next: Test Full Analysis →
            </a>
            <a href="{{ route('test.claude.logs') }}" class="bg-purple-500 text-white p-4 rounded hover:bg-purple-600 text-center">
                View Logs
            </a>
        </div>
    </div>
</body>
</html>