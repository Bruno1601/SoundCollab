<div>
    <h1 class="text-2xl font-bold text-center mb-5">Archivos</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($archivos as $archivo)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex items-center justify-center h-24 mb-4">
                    @if ($archivo->formato === 'mp3' || $archivo->formato === 'wav')
                        <audio id="player-{{ $archivo->id }}" controls wire:click="iniciarReproductor">
                            <source src="{{ Storage::url($archivo->ultimaVersion->ruta ?? $archivo->ruta) }}" type="audio/{{ $archivo->formato }}">
                            Tu navegador no soporta la reproducción de audio.
                        </audio>
                    @elseif ($archivo->formato === 'flp')
                        <i class="far fa-file-audio text-4xl text-red-500"></i>
                    @else
                        <i class="far fa-file text-4xl text-gray-500"></i>
                    @endif
                </div>

                <h2 class="text-xl font-bold">{{ $archivo->nombre }}</h2>
                <p class="text-gray-600 text-sm">{{ $archivo->ultimaVersion->fecha_subida ?? $archivo->created_at }}</p>
                {{-- <h1 class="text-2xl font-bold text-center mb-5">Versión: {{ $archivo->ultimaVersion->version ?? $archivo->version }}</h1> --}}
                <livewire:versiones-archivo :archivoId="$archivo->id" />
                <h1 class="text-2xl font-bold text-center mb-5"></h1>
                <a href="{{ route('archivos.descargar', ['archivoId' => $archivo->id]) }}" class="text-blue-500 hover:underline">Descargar</a>
                <button wire:click="eliminarArchivo({{ $archivo->id }})" class="text-red-500 hover:underline ml-2">Eliminar</button>
            </div>
        @endforeach
    </div>
</div>
