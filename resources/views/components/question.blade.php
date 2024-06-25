<div id="question{{$question->id}}" tabindex="0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
    <div class="p-6 text-gray-900 flex flex-col space-y-4">
        <h3 class="text-orange-500"> Question {{ $number }}</h3>
        <div class="flex">
            <p class="text-xl font-semibold">{{ $text }}</p>
            @if($required)
            <p class="text-xl font-semibold text-orange-500">*</p>
            @endif
        </div>
        {{ $slot }}
    </div>
</div>