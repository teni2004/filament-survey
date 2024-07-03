@props(['question', 'answers', 'options', 'label'])

@php
    $required = $question->required;
    if(isset($answers))
    {
        foreach($answers as $answer)
        {
            if($answer->question->id === $question->id)
            {
                $selected = $answer->select_one_answer->option_id;
            }
        }
    }
@endphp

<div class="mt-6 space-y-3">
    @foreach($options as $option)
        <x-select-button required="{{$required}}" name="{{$label}}" op_id='{{$option->id}}' selected="{{$selected ?? ''}}">{{$option->text}}</x-select-button>
    @endforeach
</div>