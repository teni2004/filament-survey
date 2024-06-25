@php
    $alltaken = true;
@endphp

<p style="cursor: pointer;" id="{{$header}}btn" class="text-xl font-semibold">{{ $header }} <i id="{{$header}}icon" class="fas fa-chevron-down small pl-1 rotating"></i></p>
    <div id="{{$header}}div">
        @foreach($surveys as $survey)
            @switch($header)
                @case ('Uncompleted')
                    @if(!in_array($survey, $taken))
                    <x-survey-card :survey='$survey' :taken='false'></x-survey-card>
                        @php
                        $alltaken = false;
                        @endphp
                    @endif
                    @break
                @case ('Completed')
                    @if(in_array($survey, $taken))
                    <x-survey-card :survey='$survey' :taken='true'></x-survey-card>
                    @endif
                    @break
            @endswitch
        @endforeach
        @if($header === 'Uncompleted' && $alltaken)
            <p>You have no surveys yet to be completed.</p>
        @endif
        @if($header === 'Completed' && empty($taken))
            <p>You have not completed any surveys.</p>
        @endif
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('{{$header}}btn');
        const content = document.getElementById('{{$header}}div');
        const arrow = document.getElementById('{{$header}}icon');

        button.addEventListener('click', function() {
            content.classList.toggle('hidden');
            if (content.classList.contains('hidden')) {
                arrow.style.transform = 'rotate(0deg)'; // Rotate back to 0 degrees
            } else {
                arrow.style.transform = 'rotate(180deg)'; // Rotate to 180 degrees
            }
        });
    });
</script>