@php
    $surveyTeams = $survey->teams()->pluck('team_id')->toArray();
    $userTeams = Auth::user()->teams()->pluck('team_id')->toArray();
    $hasCommonTeams = !empty(array_intersect($surveyTeams, $userTeams));
@endphp

<x-layout>
    <x-slot:heading>
        {{ $survey->name }}
    </x-slot:heading>

    @if($hasCommonTeams)
        <form id="form" method="POST" action="/admin/surveys/{{$survey->id}}/results">
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
                <button type="submit" class="rounded-xl bg-orange px-6 mb-8 py-3 text-xl font-semibold text-white shadow-sm hover:bg-orange/40">Submit</button>
            </div>
    </form>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 p-4 space-y-3">
            <p class="font-semibold">This survey has not been assigned to you.</p>
        </div>
    @endif
</x-layout>