<x-layout>
    <x-slot:heading>
    Your Response
    </x-slot:heading>

@if(count($sortedAnswers) !== 0)
    @php
    $counter = 1;
    @endphp
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        @foreach($sortedAnswers as $answer)
            <x-answer number='{{$counter}}' :answer="$answer"/>
            @php
            $counter++;
            @endphp
        @endforeach
        <br>
    </div>
@endif
</x-layout>
