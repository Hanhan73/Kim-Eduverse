<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Claude Config</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">🔧 Claude AI Configuration Test</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <a href="{{ route('test.claude.simple') }}" class="bg-blue-500 text-white p-4 rounded hover:bg-blue-600 text-center">
                📡 Test Simple Call
            </a>
            <a href="{{ route('test.claude.analysis') }}" class="bg-green-500 text-white p-4 rounded hover:bg-green-600 text-center">
                🧠 Test Full Analysis
            </a>
            <a href="{{ route('test.claude.logs') }}" class="bg-purple-500 text-white p-4 rounded hover:bg-purple-600 text-center">
                📋 View Logs
            </a>
        </div>

        @foreach($configTests as $section => $data)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">{{ ucfirst(str_replace('_', ' ', $section)) }}</h2>
            
            <div class="space-y-2">
                @foreach($data as $key => $value)
                <div class="flex border-b pb-2">
                    <div class="w-1/3 font-semibold text-gray-700">{{ $key }}:</div>
                    <div class="w-2/3 text-gray-600 font-mono">
                        @if(is_array($value))
                            {{ json_encode($value) }}
                        @elseif($value === null)
                            <span class="text-red-500">NULL</span>
                        @elseif($value === '')
                            <span class="text-red-500">EMPTY</span>
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mt-6">
            <p class="font-bold">💡 Tip:</p>
            <p>Pastikan semua config terisi dengan benar. API key harus dimulai dengan "sk-ant-api03-"</p>
        </div>
    </div>
</body>
</html>