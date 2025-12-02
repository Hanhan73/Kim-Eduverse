<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Full Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('test.claude.simple') }}" class="text-blue-500 hover:underline">← Back to Simple Test</a>
        </div>

        <h1 class="text-3xl font-bold mb-6">🧠 Full Analysis Test</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Mock Questionnaire Data</h2>
            <pre class="bg-gray-100 p-4 rounded overflow-x-auto">{{ json_encode($mockData, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Result</h2>
            
            @if($response['success'])
            <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-4">
                <p class="font-bold text-green-700">✅ SUCCESS!</p>
                <p class="text-sm text-gray-600">Duration: {{ $duration }} seconds</p>
            </div>

            @if($parsedAnalysis)
            <div class="space-y-4">
                @if(isset($parsedAnalysis['overall_summary']))
                <div class="bg-blue-50 p-4 rounded">
                    <h3 class="font-bold mb-2">Overall Summary:</h3>
                    <p class="whitespace-pre-wrap">{{ $parsedAnalysis['overall_summary'] }}</p>
                </div>
                @endif

                @if(isset($parsedAnalysis['dimension_analyses']))
                <h3 class="font-bold mb-2 mt-4">Dimension Analyses:</h3>
                @foreach($parsedAnalysis['dimension_analyses'] as $code => $analysis)
                <div class="border rounded p-4 mb-4">
                    <h4 class="font-bold text-lg mb-2">{{ ucfirst($code) }}</h4>
                    
                    @if(isset($analysis['interpretation']))
                    <div class="mb-3">
                        <span class="font-semibold">Interpretation:</span>
                        <p class="text-gray-700">{{ $analysis['interpretation'] }}</p>
                    </div>
                    @endif

                    @if(isset($analysis['recommendations']))
                    <div>
                        <span class="font-semibold">Recommendations:</span>
                        <ul class="list-disc list-inside mt-1">
                            @foreach($analysis['recommendations'] as $rec)
                            <li class="text-gray-700">{{ $rec }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif

                @if(isset($parsedAnalysis['motivational_message']))
                <div class="bg-yellow-50 p-4 rounded">
                    <h3 class="font-bold mb-2">Motivational Message:</h3>
                    <p class="italic">{{ $parsedAnalysis['motivational_message'] }}</p>
                </div>
                @endif
            </div>
            @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4">
                <p class="font-bold">⚠️ JSON Parse Failed</p>
                <p class="text-sm mt-2">Raw response:</p>
                <pre class="bg-white p-2 rounded mt-2 text-xs overflow-x-auto">{{ $response['content'] }}</pre>
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

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Full Prompt Sent</h2>
            <pre class="bg-gray-100 p-4 rounded text-xs overflow-x-auto">{{ $prompt }}</pre>
        </div>

        <a href="{{ route('test.claude.logs') }}" class="block bg-purple-500 text-white p-4 rounded hover:bg-purple-600 text-center">
            View Full Logs
        </a>
    </div>
</body>
</html>