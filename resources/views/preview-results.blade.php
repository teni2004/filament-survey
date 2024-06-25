<x-layout>
    <x-slot:heading>
        Your Response
        <br>
        <p class="text-sm text-slate-500 font-light font-sans">This survey results page is populated with example data.</p>
    </x-slot:heading>

@php
$counter = 1;
@endphp
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
    @foreach($survey->questions as $question)
        <x-mock-answer number='{{$counter}}' :question="$question"/>
        @php
        $counter++;
        @endphp
    @endforeach
    <br>
</div>
</x-layout>
