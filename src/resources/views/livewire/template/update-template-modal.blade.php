<div>
    @if ($isShowUpdate:uc:tableModal)
    <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Update :lc:table information.
                    </h3>
                    <div class="mt-2">
                        @if ($errors)
                        @foreach ($errors->all() as $error)
                        <span class="text-white bg-red-600 rounded px-2 py-1">{{ $error }}</span>
                        @endforeach
                        @endif
                        :columns
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                    <button wire:click="hideUpdate:uc:tableModal()" type="button"
                        class="text-white bg-gray-700 hover:bg-gray-600 mt-3 w-full inline-flex justify-center rounded-md boarder border-gray-300 shadow-sm px-4 py-2 font-medium">
                        Close
                    </button>
                    <button wire:click="update:uc:table({{ $id }})" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-600 mt-3 w-full inline-flex justify-center rounded-md boarder border-gray-300 shadow-sm px-4 py-2 font-medium">
                        Update
                </div>
            </div>
        </div>
    </div>
    @endif
</div>