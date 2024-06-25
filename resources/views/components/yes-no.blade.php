<style>
.selected {
    background-color: #F35D22;
}
</style>

<div class="grid lg:grid-cols-2 gap-2 mt-6">
    <x-yesno-button id="1" qid="{{$question->id}}">Yes</x-yesno-button>
    <x-yesno-button id="0" qid="{{$question->id}}">No</x-yesno-button>
    <input type="hidden" name="selected{{$question->id}}" id="selected{{$question->id}}" {{ $question->required ? 'required' : '' }}>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.yesno{{$question->id}}');
        const selectedButton = document.getElementById('selected{{$question->id}}');
        const form = document.getElementById('form');
        const hiddenInput = document.getElementById('selected{{$question->id}}');

        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                if (button.classList.contains('selected')) {
                    button.classList.toggle('selected');
                    selectedButton.value = null;
                } else {
                    buttons.forEach(function(b) {
                        if (b.id !== button.id)
                        {
                            b.classList.remove('selected');
                        }
                    });
                    button.classList.add('selected');
                    selectedButton.value = button.id;
                }
            });
        });

        form.addEventListener('submit', function(event) {
            if (!hiddenInput.value && hiddenInput.hasAttribute('required')) {
                hiddenInput.setCustomValidity('Please select an option.');
                hiddenInput.reportValidity();
                event.preventDefault(); 
                let question = document.getElementById('question{{$question->id}}');
                question.classList.add('focus:border-orange-500', 'focus:border-2');
                question.focus();
                question.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

    });
</script>