<style>
.featured:hover {
    color: white;
    background: linear-gradient(to right, #F15626, #f18526, #f7c272);
    font-weight: 550;
    border: none;
}

</style>

@if (!$taken)
<a href="/admin/surveys/{{$survey->id}}">
    <div class="featured border-black bg-white border-2 overflow-hidden shadow-sm sm:rounded-lg mt-4 p-4">
        <p> {{$survey->name }}</p>
    </div>
</a>
@elseif ($taken)
<a href="/admin/surveys/{{$survey->id}}/results">
    <div class="featured border-black bg-white border-2 overflow-hidden shadow-sm sm:rounded-lg mt-4 p-4">
        <p> {{$survey->name }}</p>
    </div>
</a>
@endif