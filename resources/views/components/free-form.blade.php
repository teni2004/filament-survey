@php
if(isset($answers))
{
    foreach($answers as $answer)
    {
        if($answer->question->id === $question->id)
        {
            $text = $answer->free_form_answer->body;
        }
    }
}
@endphp

<label for="{{$label}}"></label>
<textarea id="{{$label}}" name="{{$label}}" rows="3" class="px-1.5 bg-gray-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-orange sm:text-sm sm:leading-6" {{ $question->required ? 'required' : '' }}>{{ $text ?? '' }}</textarea>