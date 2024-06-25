@props(['label', 'options', 'question'])

<style>
    .{{$label}}.selected {
    background-color: #F35D22;
}
</style>

<div class="grid lg:grid-cols-2 gap-2 mt-6">
    @foreach($options as $option)
        <x-multiple-option label="{{$label}}" data="{{$option->id}}">{{$option->text}}</x-multiple-option>
    @endforeach
    <input type="hidden" name="selected{{ $option->question->id }}" id="selected{{ $option->question->id }}" {{ $question->required ? 'required' : '' }}>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const options = document.querySelectorAll('.{{$label}}');
        const selectedOptionsInput = document.getElementById('selected{{ $option->question->id }}');

        options.forEach(function(option) {
            option.addEventListener('click', function() {
                this.classList.toggle('selected');
                updateSelectedOptions();
            });
        });

        function updateSelectedOptions() {
            const selectedOptions = [];
            options.forEach(function(option) {
                if (option.classList.contains('selected')) {
                    selectedOptions.push(option.getAttribute('data'));
                }
            });
            selectedOptionsInput.value = selectedOptions.join(',');
        }

        form.addEventListener('submit', function(event) {
            if (!selectedOptionsInput.value && selectedOptionsInput.hasAttribute('required')) {
                selectedOptionsInput.setCustomValidity('Please select an option.');
                selectedOptionsInput.reportValidity();
                event.preventDefault(); 
                let question = document.getElementById('question{{$question->id}}');
                question.classList.add('focus:border-orange-500', 'focus:border-2');
                question.focus();
                question.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
</script>