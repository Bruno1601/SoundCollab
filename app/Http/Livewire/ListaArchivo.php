<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use Livewire\Component;
use App\Models\Proyecto;
use getID3 as GlobalGetID3;
use Illuminate\Support\Facades\Storage;
// use getID3\getID3;

class ListaArchivo extends Component
{
    public $proyectoId;

    protected $listeners = ['archivoBloqueado' => '$refresh', 'archivoDesbloqueado' => '$refresh'];


    public function render()
    {
        $proyecto = Proyecto::findOrFail($this->proyectoId);
        $archivos = $proyecto->archivos()->paginate(8);

        $getID3 = new GlobalGetID3;

        foreach ($archivos as $archivo) {
            $nombreArchivo = $archivo->nombre;
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $archivo->formato = $extension;

            // Obtener la última versión del archivo o el archivo original si no hay versiones
            $ultimaVersion = $archivo->ultimaVersion ?? $archivo;

            // Analizar la última versión del archivo
            $filePath = Storage::path($ultimaVersion->ruta);
            $fileInfo = $getID3->analyze($filePath);

            // Extraer y agregar la información deseada al archivo
            $archivo->bitrate = $fileInfo['audio']['bitrate'] ?? null;
            $archivo->sample_rate = $fileInfo['audio']['sample_rate'] ?? null;
            $archivo->lossless = $fileInfo['audio']['lossless'] ?? null;
            $archivo->codec_name = $fileInfo['audio']['codec_name'] ?? null;

            // Agregar duración, tamaño y encoder
            $archivo->duration = $fileInfo['playtime_seconds'] ?? null; // duración en segundos
            $archivo->filesize = $fileInfo['filesize'] ?? null; // tamaño del archivo en bytes
            $archivo->bits_per_sample = $fileInfo['audio']['bits_per_sample'] ?? null;
        }

        return view('livewire.lista-archivo', [
            'archivos' => $archivos,
            'proyecto' => $proyecto,
        ]);
    }

    public function reproducirArchivo($archivoId)
    {
        $archivo = Archivo::findOrFail($archivoId);

        if ($archivo->formato === 'mp3' || $archivo->formato === 'wav') {
            return response()->json(['url' => Storage::url($archivo->ruta)]);
        }

        return response()->json(['url' => null]);
    }

    public function eliminarArchivo($archivoId)
    {
        $archivo = Archivo::findOrFail($archivoId);

        // Obtener la ruta del archivo
        $rutaArchivo = $archivo->ruta;

        // Eliminar el archivo de almacenamiento
        Storage::delete($rutaArchivo);

        // Obtener el directorio del archivo
        $directorioArchivo = dirname($rutaArchivo);

        // Comprobar si el directorio está vacío antes de eliminarlo
        if (count(Storage::files($directorioArchivo)) == 0) {
            // Si el directorio está vacío, entonces eliminarlo
            Storage::deleteDirectory($directorioArchivo);
        }

        // Eliminar el registro del archivo de la base de datos
        $archivo->delete();

        session()->flash('success', 'Archivo eliminado correctamente');
    }

    public function mount()
    {
        $this->listeners['reproductorIniciado'] = 'iniciarReproductor';
    }

    public function iniciarReproductor()
    {
        $this->dispatchBrowserEvent('iniciar-reproductor');
    }




}
