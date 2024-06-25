<div class="pt-6 pl-6 text-gray-900 flex flex-col space-y-1">
    <h1 class="font-semibold">Question {{ $number }}</h1>
        <div class="flex space-x-2">
                <p>{{ $answer->question->text }}</p>
                <x-dynamic-component :component="'answer.'.$answer->type"
                :answer=$answer
                />
        </div>
</div>