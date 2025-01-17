<?php

namespace App\Livewire\:uc:table;

use App\Models\:uc:table;
use Livewire\Attributes\On;
use Livewire\Component;

class Delete:uc:tableModal extends Component
{
    public $id = null;
    public $isShowDelete:uc:tableModal = false;

    public function render()
    {
        return view('livewire.:uc:table.delete-:lc:table-modal');
    }

    #[On('showDelete:uc:tableModal')]
    public function showDelete:uc:tableModal($id)
    {
        $this->id = $id;
        $this->isShowDelete:uc:tableModal = true;
    }

    public function hideDelete:uc:tableModal()
    {
        $this->isShowDelete:uc:tableModal = false;
    }

    public function delete:uc:table()
    {
        $:lc:table = :uc:table::find($this->id);
        if ($:lc:table) {
            $:lc:table->delete();
        }
        session()->flash('success', 'Deleted successfully');

        $this->hideDelete:uc:tableModal();
        $this->dispatch('deleted-:lc:table')->to(List:uc:table::class);
    }
}
