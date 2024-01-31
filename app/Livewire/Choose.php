<?php

namespace App\Livewire;

use App\Models\Type;
use App\Models\Room;
use Livewire\Component;

class Choose extends Component
{
    public $perPage = 10;
    public $type = null;
    public function render()
    {
        return view('livewire.choose', [
            'rooms' => $this->type ? Room::where('type_id', $this->type)->paginate($this->perPage) : Room::paginate($this->perPage),
            'types' => type::all(),
        ]);
    }
    public function loadMore()
    {
        $this->perPage += 10;
    }
    public function setType($type)
    {
        if ($this->type == $type)
            $this->type = null;
        else
            $this->type = $type;
    }
}
