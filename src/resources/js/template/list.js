import { FlexGrid } from '@grapecity/wijmo.grid';

var theGrid;

document.addEventListener('livewire:init', function () {
    renderGrid();
})

Livewire.on('update:uc:tableList', (data) => {
    theGrid.itemsSource = data[0].:lc:tables;
    theGrid.refresh();
})

function renderGrid() {
    theGrid = new FlexGrid('#theGrid', {
        itemsSource: :lc:tables,
        autoGenerateColumns: false,
        columns: [
            { binding: 'id', header: 'ID' },
            {
                binding: 'edit', header: 'Edit', width: 90, isReadOnly: true, align: 'center', cellTemplate: (ctx) => {
                    return `<button type="button" class="bg-green-600 hover:bg-green-500 text-white rounded px-2"
                wire:click="$dispatch('showUpdate:uc:tableModal', {'id': ${ctx.item.id}})">Edit</button>`
                }
            },
            {
                binding: 'delete', header: 'Delete', width: 90, isReadOnly: true, align: 'center', cellTemplate: (ctx) => {
                    return `<button type="button" class="bg-red-600 hover:bg-red-500 text-white rounded px-2"
                wire:click="$dispatch('showDelete:uc:tableModal', {'id': ${ctx.item.id}})">Delete</button>`
                }
            },
            :columns
        ]
    });
}
