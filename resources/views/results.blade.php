
<x-layout>
    <x-slot:heading>
    Your Response{{ count($responses) > 1 ? 's' : '' }}
    </x-slot:heading>

@foreach($responses as $response)
@if(count($response->answers) !== 0)
    @php
    $counter = 1;
    @endphp
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        @foreach($response->answers as $answer)
            <x-answer number='{{$counter}}' :answer="$answer"/>
            @php
            $counter++;
            @endphp
        @endforeach
        <br>
    </div>
@endif
@endforeach
</x-layout>
