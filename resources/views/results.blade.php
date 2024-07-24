<x-layout>
    <x-slot:heading>
    Your Response
    </x-slot:heading>

@if(count($sortedAnswers) !== 0)
    @php
    $counter = 1;
    @endphp
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        @foreach($survey->questions as $question)
            @foreach($sortedAnswers as $answer)
                @if($answer->question->id === $question->id)
                <x-answer number='{{$counter}}' :answer="$answer"/>
                @endif
            @endforeach
            @php
                $counter++;
            @endphp
        @endforeach
        <br>
    </div>
@endif
</x-layout>
