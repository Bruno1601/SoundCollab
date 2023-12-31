<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Archivos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensaje de éxito con padding y margin reducidos -->
            @if (session('success'))
                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 rounded relative mb-1 mt-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mensajes de error en el mismo lugar y estilo que el mensaje de éxito -->
            @if ($errors->any())
                <div id="error-messages" class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 rounded relative mb-1 mt-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-14"> <!-- Espacio adicional aquí -->
                <h1 class="text-2xl font-bold text-center mb-5"></h1>
                @if ($proyecto->user_id === auth()->user()->id)
                    <livewire:colaborador-proyecto :proyectoId="$proyecto->id" />
                @endif
                <h1 class="text-2xl font-bold text-center mb-5"></h1>

                <div class="flex flex-col items-center space-y-4 sm:space-y-0 sm:flex-row sm:justify-between mb-4">
                    {{-- Botón de descarga --}}
                    <div class="w-full sm:w-auto">
                        <form method="GET" action="{{ route('archivos.descargarCarpeta', ['proyectoId' => $proyecto->id]) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-slate-400 hover:bg-slate-600 text-white font-bold py-1 px-3 rounded transition-all duration-300">
                                <i class="fas fa-download mr-1"></i> Descargar Proyecto
                            </button>
                        </form>
                    </div>

                    <div class="w-full sm:w-auto">
                        <form method="POST" action="{{ route('archivos.subir', ['proyecto' => $proyecto->id]) }}" enctype="multipart/form-data" class="w-full flex flex-col sm:flex-row items-center">
                            @csrf
                            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                            <label for="archivos" class="w-full sm:w-auto border border-gray-300 rounded cursor-pointer p-2">
                                <i class="fas fa-cloud-upload-alt mr-1"></i> Subir Archivos
                            </label>
                            <input type="file" name="archivos[]" id="archivos" class="hidden" multiple>
                            <button id="upload-button" type="submit" class="w-full sm:w-auto ml-2 sm:ml-4 bg-slate-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-md transition-all duration-300">Cargar</button>
                            <span id="file-count" class="ml-2"></span>
                        </form>
                    </div>
{{-- 
                    @if ($errors->any())
                        <div id="error-messages" class="text-red-500 mt-2 sm:mt-0 sm:ml-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                    {{-- Botón Ver Colaboradores --}}
                    <div class="w-full sm:w-auto">
                        <a href="{{ route('proyectos.colaboradores', ['proyecto' => $proyecto->id]) }}" class="w-full sm:w-auto mt-4 mb-2 sm:mt-0 sm:mb-0 sm:ml-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Ver Colaboradores
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <livewire:lista-archivo :proyectoId="$proyecto->id" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('archivos').addEventListener('change', function(e) {
            var fileCount = e.target.files.length;
            document.getElementById('file-count').textContent = fileCount > 0 ? `${fileCount} archivo${fileCount > 1 ? 's' : ''}` : '';
        });

        // Ocultar mensajes después de 5 segundos
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
