<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Survey</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" type="image/png" href="/images/favicon.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full">

    <div class="min-h-full">
        <nav class="bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="/"><img class="h-8 w-8 ml-2" src="{{ asset('images/givelify-logo-only-orange.png') }}" alt="Your Company"></a>
                        </div>
                        <div class="ml-10 flex items-baseline space-x-4">
                            <x-nav-link href="/" :active="request()->is('/')">Surveys</x-nav-link>
                            <x-nav-link href="/admin/surveys" :active="request()->is('/admin/surveys')">Admin</x-nav-link>
                            @if(request()->segment(2) === 'preview-results' || request()->segment(2) === 'preview')
                            <x-nav-link href="/admin/surveys/{{request()->segment(1)}}/edit" :active="request()->is('/admin/surveys/{{request()->segment(1)}}/edit')">Edit Survey</x-nav-link>
                            @endif
                            @if(request()->segment(4) === 'preview-results' || request()->segment(4) === 'preview' || request()->segment(4) === 'stats')
                            <x-nav-link href="/admin/surveys/{{request()->segment(3)}}/stats" :active="request()->is('/admin/surveys/{{request()->segment(3)}}/stats')">Stats</x-nav-link>
                            @endif
                            @if(request()->segment(4) === 'results' || request()->segment(4) === 'edit-response')
                            <x-nav-link href="/admin/surveys/{{request()->segment(3)}}/edit-response" :active="request()->is('/admin/surveys/{{request()->segment(3)}}/edit-response')">Edit Response</x-nav-link>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-baseline">
                        <form method="POST" action="/admin/logout">
                        @csrf
                            <button> <x-nav-link :active="request()->is('login')">Sign Out</x-nav-link> </button>
                        </form>
                    </div>
            </div>
        </div>
    </nav>
    <div class="bg-orange-500 pb-0.5 mx-1">
    </div>

    <header class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 sm:flex sm:justify-between">
            <h1 class="text-xxl font-bold tracking-tight text-gray-900 pl-2">{{ $heading }}</h1>
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        {{ $slot }}
        </div>
    </main>
    </div>
    </body>
</html>