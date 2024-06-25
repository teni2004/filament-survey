@php
$required = $question->required;
@endphp

<div class="mt-6 space-y-3">
    @foreach($options as $option)
        <x-select-button required="{{$required}}" name="{{$label}}" op_id='{{$option->id}}'>{{$option->text}}</x-select-button>
    @endforeach
</div>