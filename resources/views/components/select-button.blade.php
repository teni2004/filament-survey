
@props(['op_id', 'name', 'required'])

<div class="flex items-center gap-x-3">
    <input id="{{ $op_id }}" value="{{ $op_id }}" name="{{ $name }}" type="radio" class="h-4 w-4 border-gray-300 text-orange focus:ring-orange" {{ $required ? 'required' : '' }}>
    <label for="{{ $op_id }}" class="block text-md font-medium leading-6 text-gray-900">{{ $slot }}</label>
</div>