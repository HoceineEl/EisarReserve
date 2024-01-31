<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Room;
use Livewire\Component;

class Choose extends Component
{
    public $perPage = 10;
    public $building = null;
    public function render()
    {
        return view('livewire.choose', [
            'rooms' => $this->building ? Room::where('building_id', $this->building)->paginate($this->perPage) : Room::paginate($this->perPage),
            'buildings' => Building::all(),
        ]);
    }
    public function loadMore()
    {
        $this->perPage += 10;
    }
    public function setBuilding($building)
    {
        if ($this->building == $building)
            $this->building = null;
        else
            $this->building = $building;
    }
}
