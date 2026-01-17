@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">

    {{-- MOBILE --}}
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
        <span class="px-4 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md">
            Prev
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}"
            class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-indigo-50">
            Prev
        </a>
        @endif

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
            class="px-4 py-2 ml-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-indigo-50">
            Next
        </a>
        @else
        <span class="px-4 py-2 ml-3 text-sm text-gray-400 bg-white border border-gray-300 rounded-md">
            Next
        </span>
        @endif
    </div>

    {{-- DESKTOP --}}
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-end">
        <span class="inline-flex items-center gap-1">

            {{-- PREVIOUS --}}
            @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-full border text-gray-300">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="w-9 h-9 flex items-center justify-center rounded-full border text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            @endif

            {{-- PAGE NUMBERS --}}
            @foreach ($elements as $element)
            @if (is_string($element))
            <span class="w-9 h-9 flex items-center justify-center text-gray-400">
                {{ $element }}
            </span>
            @endif

            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <span
                class="w-9 h-9 flex items-center justify-center rounded-full bg-indigo-600 text-white font-semibold shadow">
                {{ $page }}
            </span>
            @else
            <a href="{{ $url }}"
                class="w-9 h-9 flex items-center justify-center rounded-full border text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                {{ $page }}
            </a>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- NEXT --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="w-9 h-9 flex items-center justify-center rounded-full border text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            @else
            <span class="w-9 h-9 flex items-center justify-center rounded-full border text-gray-300">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            @endif

        </span>
    </div>
</nav>
@endif