<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Room;
use Livewire\Component;

class Choose extends Component
{
    public function render()
    {
        return view('livewire.choose', [
            'rooms' => Room::all(),
            'buildings' => Building::all(),
        ]);
    }
}
