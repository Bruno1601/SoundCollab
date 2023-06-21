<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>


        <div class="inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form wire:submit.prevent="searchUsers">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <p class="mb-4">Nombre Colaborador</p>
                    <div class="mb-4">
                        <input class="w-full font-medium text-sm text-gray-700 dark:text-gray-300" type="text" wire:model="search" placeholder="Buscar usuarios...">
                    </div>

                    @foreach ($searchResults as $user)
                        <div>
                            <span>{{ $user->name }}</span>
                            <button class="bg-indigo-600 py-1 px-2 rounded-lg text-white text-xs font-bold uppercase text-center" wire:click="addColaborador({{ $user->id }})"><i class="fas fa-user-plus mr-1"></i></button>
                        </div>
                    @endforeach

                    @if (session()->has('mensaje'))
                        <div class="border border-red-600 bg-red-100 text-red-600 font-bold p-1 my-2">
                        {{session('mensaje')}}
                        </div>
                    @endif

                    @if (session()->has('mensajeExito'))
                        <div class="border border-green-600 bg-green-100 text-green-600 font-bold p-1 my-2">
                            {{ session('mensajeExito') }}
                        </div>
                    @endif

                    <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-800 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Buscar</button>
                        </span>

                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button wire:click="cerrarModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cerrar</button>
                        </span>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

