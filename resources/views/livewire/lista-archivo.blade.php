<!-- ListaArchivo.blade.php -->
<div>
    <h1 class="text-2xl font-bold text-center mb-5">Archivos</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($archivos as $archivo)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex items-center justify-center h-24 mb-4">
                    @if ($archivo->formato === 'mp3')
                        <i class="far fa-file-audio text-4xl text-blue-500"></i>
                    @elseif ($archivo->formato === 'wav')
                        <i class="far fa-file-audio text-4xl text-yellow-500"></i>
                    @elseif ($archivo->formato === 'flp')
                        <i class="far fa-file-audio text-4xl text-red-500"></i>
                    @else
                        <i class="far fa-file text-4xl text-gray-500"></i>
                    @endif
                </div>
                <h2 class="text-xl font-bold">{{ $archivo->nombre }}</h2>
                <p class="text-gray-600">{{ $archivo->formato }}</p>
                <p class="text-gray-600 text-sm">{{ $archivo->created_at }}</p>
                <a href="{{ route('archivos.descargar', ['archivoId' => $archivo->id]) }}" class="text-blue-500 hover:underline">Descargar</a>
            </div>
        @endforeach
    </div>
</div>

