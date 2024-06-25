<x-filament-panels::page>


@vite(['resources/css/app.css', 'resources/js/app.js', 'public/css/filament/filament/app.css'])

<div class="bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg mb-8">
    <div class="p-6 text-white flex flex-col space-y-4">
        <p>Your survey is available to preview at <a href="{{ $record->preview_url }}" class="hover:text-orange hover:underline">{{ $record->preview_url }}</a></p>
    </div>
</div>


</x-filament-panels::page>
