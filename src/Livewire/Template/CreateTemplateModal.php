<?php

namespace App\Livewire\:uc:table;

use App\Models\:uc:table;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create:uc:tableModal extends Component
{
    :columns
    public $id = null;
    public $isShowCreate:uc:tableModal = false;

    public function render()
    {
        return view('livewire.:uc:table.create-:lc:table-modal');
    }

    #[On('showCreate:uc:tableModal')]
    public function showCreate:uc:tableModal()
    {
        $this->isShowCreate:uc:tableModal = true;
    }

    public function hideCreate:uc:tableModal()
    {
        $this->isShowCreate:uc:tableModal = false;
    }

    public function create:uc:table()
    {
        $validated = $this->validate();

        $:lc:table = (new :uc:table)->fill($validated);
        $:lc:table->save();
        session()->flash('success', 'Created successfully');

        $this->reset();
        $this->hideCreate:uc:tableModal();
        $this->dispatch('created-:lc:table')->to(List:uc:table::class);
    }
}
