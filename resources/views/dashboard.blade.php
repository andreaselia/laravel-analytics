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
                @each('analytics::stats.card', $stats, 'stat')
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @include('analytics::data.pages-card')
                @include('analytics::data.sources-card')
                @include('analytics::data.users-card')
                @include('analytics::data.devices-card')
            </div>
        </div>
    </div>
</body>
</html>
