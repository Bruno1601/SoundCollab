<div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
    <div class="flex flex-col items-center justify-center mb-4" wire:ignore>
        @if ($archivo->formato === 'mp3' || $archivo->formato === 'wav')
            <!-- Reproductor de audio -->
            <div id="waveform-{{ $archivo->id }}" class="w-full bg-white-200 overflow-hidden my-4" data-src="{{ Storage::url($archivo->ultimaVersion->ruta) }}" style="height: 150px;"></div>
            <!-- Botones de control de audio -->
            <div class="flex items-center space-x-2">
                <button id="play-{{ $archivo->id }}" class="bg-slate-500 hover:bg-slate-700 text-white font-bold py-1 px-2 rounded">
                    <i class="fas fa-play fa-xs"></i>
                </button>
                <button id="pause-{{ $archivo->id }}" class="bg-slate-500 hover:bg-slate-700 text-white font-bold py-1 px-2 rounded">
                    <i class="fas fa-pause fa-xs"></i>
                </button>
            </div>

            <!-- Información del archivo -->
            <div class="text-xs text-gray-500">
                <p>Versión: {{ $ultimaVersion->version ?? 'Desconocido' }}</p>
                <p>Bitrate: {{ $bitrate ?? 'Desconocido' }}</p>
                <p>Tasa de muestreo: {{ $sample_rate ?? 'Desconocido' }}</p>
                <p>Duración: {{ gmdate("i:s", $duration) }}</p>
                <p>Tamaño del archivo: {{ round($filesize / 1024 / 1024, 2) }} MB</p>
                @if ($bits_per_sample)
                    <p class="text-gray-500 text-xs">Profundidad de bits: {{ $bits_per_sample }} bits</p>
                @endif
            </div>
        @else
            <i class="far fa-file-audio text-4xl text-red-500"></i>
            <p class="text-xs text-gray-500">Versión: {{ $archivo->ultimaVersion->version ?? 'Desconocido' }}</p>
        @endif
    </div>

    <h2 class="text-lg sm:text-xl lg:text-base font-bold">{{ $archivo->nombre }}</h2>
    <p class="text-xs sm:text-sm text-gray-600">{{ $archivo->ultimaVersion->fecha_subida ?? $archivo->created_at }}</p>

    <div class="w-full sm:w-auto min-h-[100px] flex justify-center mt-4">
        <livewire:versiones-archivo :archivoId="$archivo->id" />
    </div>

    @if (!$archivo->bloqueado || optional($archivo->bloqueadoPor)->id === optional(auth()->user())->id)
        <div class="flex justify-center space-x-4 mt-2">
            <a href="{{ route('versiones.descargar', ['versionId' => $archivo->ultimaVersion->id]) }}" class="text-blue-500 hover:underline sm:mx-2">
                <i class="fas fa-download"></i>
            </a>

            <button wire:click="eliminarArchivo({{ $archivo->id }})" class="text-red-500 hover:underline sm:mx-2">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-center mt-2">
        @if($archivo->bloqueado)
            @if(optional($archivo->bloqueadoPor)->id === optional(auth()->user())->id)
                <button data-id="desbloquear-button-{{ $archivo->id }}" wire:click="desbloquearArchivo" class="text-blue-500 hover:underline sm:mx-2">
                    <i class="fas fa-unlock"></i> Desbloquear
                </button>
            @else
                <p class="text-xs sm:text-sm text-red-500 ml-2">Archivo bloqueado por {{ $archivo->bloqueadoPor->name }}</p>
            @endif
        @else
            @if (optional(auth()->user())->id)
                <button data-id="bloquear-button-{{ $archivo->id }}" wire:click="bloquearArchivo" class="text-blue-500 hover:underline sm:mx-2">
                    <i class="fas fa-lock"></i> Bloquear
                </button>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/wavesurfer.js@7/dist/wavesurfer.min.js"></script>
<script>
document.addEventListener('livewire:load', function() {
    let waveforms = document.querySelectorAll('[id^="waveform-"]');

    waveforms.forEach(waveform => {
        let id = waveform.id.split('-')[1];

        // Verifica si Wavesurfer.js ya ha sido inicializado para este audio
        if (window['wavesurfer-' + id]) {
            // Si ya ha sido inicializado, no hagas nada
            return;
        }

        let url = waveform.getAttribute('data-src');

        let wavesurfer = WaveSurfer.create({
            container: '#waveform-' + id,
            waveColor: '#D8D8D8',
            progressColor: '#000000',
            cursorColor: '#000000',
            cursorWidth: 0.5,
            height: 150,
            minPxPerSec: 100,
            pixelRatio: 1,
            barWidth: 2,
            barGap: 2,
            responsive: true,
            backgroundColor: '#F7FAFC'
        });

        wavesurfer.load(url);

        // Crea la interacción entre los botones y Wavesurfer
        document.getElementById('play-' + id).addEventListener('click', () => wavesurfer.play());
        document.getElementById('pause-' + id).addEventListener('click', () => wavesurfer.pause());

        // Guarda una referencia a la instancia de Wavesurfer.js en una variable global
        window['wavesurfer-' + id] = wavesurfer;
    });
});
</script>
@endpush
