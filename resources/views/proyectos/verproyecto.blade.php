<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Archivos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">

                    <h1 class="text-2xl font-bold text-center mb-5"></h1>

                    <livewire:colaborador-proyecto :proyectoId="$proyecto->id"/>

                    <h1 class="text-2xl font-bold text-center mb-5"></h1>

                    {{-- subir archivos --}}
                    <form method="POST" action="{{ route('archivos.subir', ['proyecto' => $proyecto->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">                        @csrf
                        <label for="archivos" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            <i class="fas fa-cloud-upload-alt mr-1"></i> Subir Archivos
                        </label>
                        <input type="file" name="archivos[]" id="archivos" class="hidden" multiple>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"></button>
                    </form>
                    @if (session('success'))
                        <div class="text-green-500 mt-4">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
