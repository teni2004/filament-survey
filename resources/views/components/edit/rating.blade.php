@vite(['resources/css/app.css', 'resources/js/app.js'])
@props(['rating_options', 'label', 'question', 'answers'])

@php
$rating = $answers->where('question_id', $question->id)->get()[0]->rating_answer->rating;
@endphp
<label for="{{$label}}"></label>
<div class="flex flex-col pb-3">
    <input type="range" id="{{$label}}" name="{{$label}}" min="{{$rating_options->min_value}}" max="{{$rating_options->max_value}}" value="{{$rating}}" oninput="output.value = {{$label}}.value" style="accent-color:black" required>
</div>
<div class="flex items-center justify-center pb-2">
    <output id="output" name="output" for="{{$label}}" class="text-4xl font-bold text-orange">{{$rating}}</output>
</div>

<script> 
    document.addEventListener('DOMContentLoaded', function() {
        const rangeInput = document.getElementById('{{$label}}');
        const output = document.getElementById('output');

        rangeInput.addEventListener('input', function() {
            output.value = rangeInput.value;
            const rangeWidth = rangeInput.offsetWidth;
            const rangeMin = parseInt(rangeInput.min);
            const rangeMax = parseInt(rangeInput.max);
            const newPoint = ((rangeInput.value - rangeMin) / (rangeMax - rangeMin)) * rangeWidth;
            output.style.left = `calc(${newPoint}px - ${output.offsetWidth / 2}px)`;
        });
    });
</script>