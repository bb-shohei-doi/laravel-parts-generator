<?php

namespace App\Livewire\:uc:table;

use App\Models\:uc:table;
use Livewire\Attributes\On;
use Livewire\Component;

class List:uc:table extends Component
{
    #[On('created-:lc:table')]
    #[On('updated-:lc:table')]
    #[On('deleted-:lc:table')]
    public function update:uc:tableList()
    {
        $this->dispatch('update:uc:tableList', [
            ':lc:tables' => :uc:table::all(),
        ]);

        $this->skipRender();
    }

    public function render()
    {
        return view('livewire.:uc:table.list-:lc:table', [
            ':lc:tables' => :uc:table::all(),
        ]);
    }
}
