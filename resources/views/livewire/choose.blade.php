<div>
    <div class="flex gap-4 justify-center py-10 overflow-x-auto">
        @foreach ($types as $type)
            <div class="bg-indigo-950 cursor-pointer w-fit px-2 py-1 rounded-md flex items-center gap-2"
                wire:click="setType({{ $type->id }})">
                <p>{{ $type->name }}</p>
                @if ($type->id == $this->type)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                            clip-rule="evenodd" />
                    </svg>
                @endif
            </div>
        @endforeach
    </div>
    <div class="flex flex-wrap justify-center items-center gap-10">
        @foreach ($rooms as $room)
            <div
                class="w-full sm:w-80  rounded-lg bg-slate-100 dark:bg-gray-900 overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                <div class="w-full h-60 overflow-hidden">
                    <img class="object-cover w-full h-full transform scale-100" src="{{ asset($room->image) }}"
                        alt="{{ $room->number }} - {{ $room->building->name }}">
                </div>
                <div class="px-4 py-2 flex flex-col gap-5">
                    <h3 class="font-bold text-xl">{{ $room->number }} - {{ $room->building->name }}</h3>

                    <p class="text-sm flex gap-2 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.544l.062.029.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z"
                                clip-rule="evenodd" />
                        </svg>

                        {{ $room->building->address }}
                    </p>
                    <div class="flex justify-between pb-3">
                        <p class="bg-amber-700 px-2 py-1 rounded-md text-white font-semibold">{{ $room->type->name }}
                        </p>
                        @if ($room->isReservedNow())
                            <p class="bg-red-400 px-2 py-1 rounded-md text-white font-semibold">Reserved Now</p>
                        @else
                            <p class="bg-green-500 px-2 py-1 rounded-md text-white font-semibold">Available Now</p>
                        @endif
                    </div>
                    <div class="flex justify-end">
                        <a class="px-2 py-[2px] rounded-md bg-blue-500 text-white font-semibold"
                            href="{{ route('filament.admin.resources.book-nows.create', ['room' => $room->id]) }}">Choose
                            this</a>
                    </div>
                </div>
            </div>
        @endforeach
        <div x-intersect="$wire.loadMore()">
        </div>
    </div>
    @livewireScripts

</div>
