@php
$cursor = 1;
$counter = $answer->multiple_choice_answers->count();
@endphp

<div class="flex">
    @foreach($answer->multiple_choice_answers as $mcanswer)
        <p class="text-orange font-semibold">{{ $mcanswer->option->text }}{{ $counter !== $cursor ? ' +' : ''}}&nbsp;</p>
        @php
        $cursor++;
        @endphp
    @endforeach
</div>