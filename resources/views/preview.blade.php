<x-layout>
    <x-slot:heading>
        {{ $survey->name }}
    </x-slot:heading>

    <form id="form" method="POST" action="/admin/surveys/{{$survey->id}}/preview-results">
    @csrf
        @php
            $counter = 1;
        @endphp

        @foreach($survey->questions as $question)
            <x-question :question="$question" number='{{$counter}}' required='{{$question->required}}'>
                <x-slot name="text">{{$question->text}}</x-slot>
                <x-dynamic-component :component='$question->type' 
                    :options="$question->type === 'select-one' || $question->type === 'multiple-choice' ? $question->options : null"
                    :rating_options="$question->type === 'rating' ? $question->rating_options : null"
                    :label="$question->label"
                    :question="$question"
                >
                </x-dynamic-component>
            </x-question>
            @php
                $counter++;
            @endphp
        @endforeach
        <div class="flex items-center justify-center">
            <button class="rounded-xl bg-orange px-6 mb-8 py-3 text-xl font-semibold text-white shadow-sm hover:bg-orange/40">Submit</button>
        </div>
    </form>

</x-layout>