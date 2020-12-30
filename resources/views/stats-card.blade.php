<div class="shadow-sm bg-white rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-5 flex flex-col">
        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $title }}</h3>
        <div class="mt-2 flex items-center">
            <div class="text-3xl font-extrabold leading-none text-gray-700">{{ $value }}</div>
            <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                @include({{ $direction === Analytics::INCREASE ? 'increase' : 'decrease' }} . '-icon')
                {{ $percentage }}%
            </div>
        </div>
    </div>
</div>
