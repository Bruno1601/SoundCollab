<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">


            @forelse ($proyectos as $proyecto )

            <div class="p-6 border-b-8 border-gray-100 text-gray-900 dark:text-gray-100 md:flex md:justify-between md:items-center">
                <div class="space-y-3">
                    <a href="#" class="text-lg font-bold">
                    {{$proyecto->nombre_proyecto}}
                    </a>
                    <p class="text-sm text-gray-600 font-bold">{{$proyecto->descripcion}}</p>
                    <p class="text-xs text-gray-500 ">Creado el: {{$proyecto->created_at->format('d/m/Y')}}</p>
                </div>

                <div class="flex flex-col md:flex-row items-stretch gap-3  mt-5 md:mt-0">
                    <a href="{{ route('proyectos.verproyecto', $proyecto->id) }}" class="bg-gray-700 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">Ir a proyecto</a>
                    <a href="{{route('proyectos.edit', $proyecto->id)}}" class="bg-indigo-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center"><i class="fas fa-edit"></i>
                    </a>
                    <button
                    wire:click="$emit('mostrarAlerta',{{$proyecto->id}})" class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center"><i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            @empty
                <p class="p-3 text-center text-sm text-gray-600">No hay proyectos que mostrar</p>
            @endforelse

    </div>

    <div class="flex justify-center mt-10">
        {{$proyectos->links()}}
    </div>
</div>

@push('scripts')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('mostrarAlerta', proyectoId => {
            Swal.fire({
                title: 'Estás seguro?',
                text: "Un proyecto eliminado no se puede recuperar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Eliminar!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    //eliminar proyecto
                    Livewire.emit('eliminarProyecto', proyectoId)

                    Swal.fire(
                    'Eliminado!',
                    'Tu proyecto ha sido eliminado.',
                    'success'
                    )
                }
            })
        });


    </script>

@endpush

