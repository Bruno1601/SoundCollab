<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Archivos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-center mb-5"></h1>
            @if ($proyecto->user_id === auth()->user()->id)
            <livewire:colaborador-proyecto :proyectoId="$proyecto->id" />
            @endif
            <h1 class="text-2xl font-bold text-center mb-5"></h1>

            {{-- subir archivos --}}
            <div class="flex items-center justify-between mb-4">
                <form method="POST" action="{{ route('archivos.subir', ['proyecto' => $proyecto->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                    <label for="archivos" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Subir Archivos
                    </label>
                    <input type="file" name="archivos[]" id="archivos" class="hidden" multiple>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2">Cargar</button>
                </form>

                @if ($errors->any())
                    <div id="error-messages" class="text-red-500 ml-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div id="success-message" class="text-green-500 ml-4">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Botón de descarga --}}
                <div>
                    <form method="GET" action="{{ route('archivos.descargarCarpeta', ['proyectoId' => $proyecto->id]) }}">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-download mr-1"></i> Descargar proyecto
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:lista-archivo :proyectoId="$proyecto->id" />
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ocultar mensajes después de 5 segundo
        setTimeout(function() {
            var errorMessages = document.getElementById('error-messages');
            if (errorMessages) {
                errorMessages.style.display = 'none';
            }

            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
</x-app-layout>
