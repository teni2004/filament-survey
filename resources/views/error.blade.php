<x-layout>
    <x-slot:heading>
    Error
    </x-slot:heading>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 p-4 space-y-1">
        @if ($name == 'alreadydone')
        <h1 class="font-bold text-2xl">You have already submitted a response</h1>
        <p>You can click <a href="/admin/surveys/{{$survey->id}}/edit-response" class="hover:underline hover:text-orange">here</a> to edit your response.</p>
        @endif
    </div>
</x-layout>