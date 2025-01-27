<div x-data="{ show: @entangle('isShowDelete:uc:tableModal') }">
    <div x-show="show" x-transition:enter="modal-enter" x-transition:leave="modal-leave"
        class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click.outside="show = false"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Confirm Delete :lc:table information.
                    </h3>
                    <div class="text-center text-5xl">
                        <span class="dli-exclamation-circle text-red-500"><span class="text-red-500"></span></span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this :lc:table information?
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                    <button wire:click="hideDelete:uc:tableModal()" type="button"
                        class="text-white bg-gray-700 hover:bg-gray-600 mx-3 mt-3 w-full inline-flex justify-center rounded-md boarder border-gray-300 shadow-sm px-4 py-2 font-medium">
                        Close
                    </button>
                    <button wire:click="delete:uc:table({{ $id }})" type="button"
                        class="text-white bg-red-700 hover:bg-red-600 mx-3 mt-3 w-full inline-flex justify-center rounded-md boarder border-gray-300 shadow-sm px-4 py-2 font-medium">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
