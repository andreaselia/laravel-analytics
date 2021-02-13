<div class="shadow-sm bg-white rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-5">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Pages</h3>
    </div>
    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
        <div>Page</div>
        <div>Users</div>
    </div>
    <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
        @foreach ($pages as $page)
            <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                <div class="pr-5 text-sm leading-5 text-gray-800 truncate">
                    <a href="{{ $page->page }}" target="_blank" class="hover:underline">
                        {{ $page->page }}
                    </a>
                </div>
                <div class="text-sm leading-5 text-gray-600">{{ $page->users }}</div>
            </div>
        @endforeach
    </div>
</div>
