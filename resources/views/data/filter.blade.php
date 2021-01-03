<div class="relative inline-block text-left">
    <div>
        <button type="button" class="inline-flex justify-center w-full rounded-md shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200" id="filter-button">
            {{ $periods[$period] }}
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="display: none;" id="filter-dropdown">
        <div class="p-2" role="menu" aria-orientation="vertical" aria-labelledby="filter-button">
            @foreach ($periods as $key => $value)
                <a href="{{ url(config('analytics.prefix')) }}?period={{ $key }}" class="block px-4 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">{{ $value }}</a>
            @endforeach
        </div>
    </div>
</div>
