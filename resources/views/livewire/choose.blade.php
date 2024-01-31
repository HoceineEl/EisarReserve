<div>
    <div class="flex w-full flex-wrap gap-10 hey">
        @foreach ($rooms as $room)
            <img class="w-12 h-12" src="{{ asset($room->image) }}" alt="">
        @endforeach
    </div>
</div>
