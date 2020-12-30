<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Analytics</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="min-h-screen bg-gray-100 text-gray-500 py-6 flex flex-col sm:py-16">
        <div class="w-full sm:max-w-5xl sm:mx-auto">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5 flex flex-col">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Unique Users</h3>
                        <div class="mt-2 flex items-center">
                            <div class="text-3xl font-extrabold leading-none text-gray-700">51,900</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Increased by</span>
                                62%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5 flex flex-col">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Page Views</h3>
                        <div class="mt-2 flex items-center">
                            <div class="text-3xl font-extrabold leading-none text-gray-700">157,000</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Decreased by</span>
                                13%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5 flex flex-col">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Bounce Rate</h3>
                        <div class="mt-2 flex items-center">
                            <div class="text-3xl font-extrabold leading-none text-gray-700">60%</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Increased by</span>
                                12%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5 flex flex-col">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Average Visit</h3>
                        <div class="mt-2 flex items-center">
                            <div class="text-3xl font-extrabold leading-none text-gray-700">1m 23s</div>
                            <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Decreased by</span>
                                19%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Pages</h3>
                    </div>
                    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
                        <div>Page</div>
                        <div>Users</div>
                    </div>
                    <div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between border-b hover:bg-gray-50 border-gray-200">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">
                                <a title="/" href="https://laravel-analytics.com" target="_blank" class="hover:underline">/</a>
                            </div>
                            <div class="text-sm leading-5 text-gray-600">25.8k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">
                                <a title="/download" href="https://laravel-analytics.com/download" target="_blank" class="hover:underline">/download</a>
                            </div>
                            <div class="text-sm leading-5 text-gray-600">12k</div>
                        </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Sources</h3>
                    </div>
                    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
                        <div>Page</div>
                        <div>Users</div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">
                                <div class="flex items-center">
                                    <img alt="https://github.com/" class="w-4 h-4 mr-3" src="https://github.githubassets.com/favicons/favicon.svg" />
                                    <a title="https://github.com/" href="https://laravel-analytics.com" target="_blank" class="hover:underline">github.com</a>
                                </div>
                            </div>
                            <div class="text-sm leading-5 text-gray-600">32.4k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">
                                <div class="flex items-center">
                                    <img alt="https://twitter.com/" class="w-4 h-4 mr-3" src="https://abs.twimg.com/favicons/twitter.ico" />
                                    <a title="https://twitter.com/" href="https://laravel-analytics.com" target="_blank" class="hover:underline">t.co</a>
                                </div>
                            </div>
                            <div class="text-sm leading-5 text-gray-600">17.8k</div>
                        </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Users</h3>
                    </div>
                    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
                        <div>Country</div>
                        <div>Users</div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">United Kingdom</div>
                            <div class="text-sm leading-5 text-gray-600">24.9k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">United States</div>
                            <div class="text-sm leading-5 text-gray-600">8.5k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">Germany</div>
                            <div class="text-sm leading-5 text-gray-600">3.8k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">Netherlands</div>
                            <div class="text-sm leading-5 text-gray-600">1.7k</div>
                        </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white rounded-lg overflow-hidden">
                    <div class="px-4 sm:px-6 py-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Devices</h3>
                    </div>
                    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
                        <div>Type</div>
                        <div>Users</div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">Desktop</div>
                            <div class="text-sm leading-5 text-gray-600">192k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">Mobile</div>
                            <div class="text-sm leading-5 text-gray-600">133k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">Laptop</div>
                            <div class="text-sm leading-5 text-gray-600">75.5k</div>
                        </div>
                        <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                            <div class="pr-5 text-sm leading-5 text-gray-800 truncate">Tablet</div>
                            <div class="text-sm leading-5 text-gray-600">16.3k</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
