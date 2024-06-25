<x-layout>
    <x-slot:heading>
        {{ Auth::user()->name }}'s Assigned Surveys
    </x-slot:heading>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 p-4 space-y-3">
        @if($surveys === null)
        <p class="font-semibold">You have no assigned surveys.</p>
        @else
        <x-dropdown header="Uncompleted" :surveys="$surveys" :taken="$taken"/>
        <x-dropdown header="Completed" :surveys="$surveys" :taken="$taken"/>
        @endif
    </div>
</x-layout>

<style>
    .small {
        font-size: 0.9rem;
    }

    .hidden {
        display: none;
    }

    .rotating {
            transition: transform 0.5s ease;
    }
</style>