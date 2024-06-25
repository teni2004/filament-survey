@props(['label', 'data'])

<p data={{$data}} class="{{$label}} text-center hover:bg-orange-500 bg-orange-500/45 shadow-sm sm:rounded-xl p-4 {{ ($chosen ?? '') }}">{{ $slot }}</p>