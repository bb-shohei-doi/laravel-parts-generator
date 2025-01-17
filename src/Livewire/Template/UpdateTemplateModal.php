<?php

namespace App\Livewire\:uc:table;

use App\Models\:uc:table;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Update:uc:tableModal extends Component
{
    :columns

    public $id = null;
    public $isShowUpdate:uc:tableModal = false;

    public function render()
    {
        $:lc:table = :uc:table::find($this->id);
        if ($:lc:table) {
            :copyProperties
        }
        return view('livewire.:uc:table.update-:lc:table-modal');
    }

    #[On('showUpdate:uc:tableModal')]
    public function showUpdate:uc:tableModal($id)
    {
        $this->id = $id;
        $this->isShowUpdate:uc:tableModal = true;
    }

    public function hideUpdate:uc:tableModal()
    {
        $this->isShowUpdate:uc:tableModal = false;
    }

    public function update:uc:table()
    {
        $validated = $this->validate();

        $:lc:table = :uc:table::find($this->id);
        if ($:lc:table) {
            $:lc:table->fill($validated);
            $:lc:table->save();
        }
        session()->flash('success', 'Updated successfully');

        $this->hideUpdate:uc:tableModal();
        $this->dispatch('updated-:lc:table')->to(List:uc:table::class);
    }
}
