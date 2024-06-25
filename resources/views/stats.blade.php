<x-layout>
    <x-slot:heading>
    {{$survey->name}} Statistics
    </x-slot:heading>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 p-4 space-y-4">
    @foreach($survey->questions as $question)
        @if($question->type != 'free-form')
            <div>
            @php
            $id = (string) $question->id;
            @endphp
            @switch ($question->type)
                @case('rating')
                    <p class="font-semibold"> {{$question->text}} </p>
                    <p> The average rating is <span class="text-orange">{{$statistics[$id]}}</span></p>
                    @break
                @case('yes-no')
                    <p class="font-semibold"> {{$question->text}} </p>
                    @if($statistics[$id][0] !== '0%')
                        <p class="text-orange"> {{$statistics[$id][0]}} <span class="text-black">of respondants said </span> yes.</p>
                    @endif
                    @if($statistics[$id][1] !== '0%')
                        <p class="text-orange"> {{$statistics[$id][1]}} <span class="text-black">of respondants said </span> no. </p>
                    @endif
                    @break
                @case('multiple-choice')
                    <p class="font-semibold"> {{$question->text}} </p>
                        @foreach($statistics[$id] as $stat)
                            @if($stat[1] !== '0%')
                                <p class="text-orange">{{$stat[1]}} <span class="text-black">of respondants chose</span> {{$stat[0]}}</p>
                            @endif
                        @endforeach
                    @break
                @case('select-one')
                <p class="font-semibold"> {{$question->text}} </p>
                    @foreach($statistics[$id] as $stat)
                        @if($stat[1] !== '0%')
                            <p class="text-orange">{{$stat[1]}} <span class="text-black">of respondants chose</span> {{$stat[0]}}</p>
                        @endif
                    @endforeach
            @endswitch
            </div>
        @endif
    @endforeach
    </div>
</x-layout>