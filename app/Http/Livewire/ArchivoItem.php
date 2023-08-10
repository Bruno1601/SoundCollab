<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use getID3 as GlobalGetID3;

class ArchivoItem extends Component
{
    public Archivo $archivo;

    protected $listeners = ['archivoBloqueado' => 'handleArchivoBloqueado', 'archivoDesbloqueado' => 'handleArchivoDesbloqueado'];

    public function bloquearArchivo()
    {
        $this->archivo->bloqueado = true;
        $this->archivo->bloqueado_por = auth()->id();
        $this->archivo->save();

        $this->emit('archivoBloqueado', $this->archivo->id);
    }

    public function desbloquearArchivo()
    {
        $this->archivo->bloqueado = false;
        $this->archivo->bloqueado_por = null;
        $this->archivo->save();

        $this->emit('archivoDesbloqueado', $this->archivo->id);
    }

    public function handleArchivoBloqueado($archivoId)
    {
        if ($this->archivo->id === $archivoId) {
            $this->archivo->refresh();
        }
    }

    public function handleArchivoDesbloqueado($archivoId)
    {
        if ($this->archivo->id === $archivoId) {
            $this->archivo->refresh();
        }
    }

    public function render()
    {
        // ...

        $getID3 = new GlobalGetID3;

        $archivo = $this->archivo; // Supongo que obtienes el archivo de alguna manera

        // Obtener la última versión del archivo o el archivo original si no hay versiones
        $ultimaVersion = $archivo->ultimaVersion ?? $archivo;

        // Analizar la última versión del archivo
        $filePath = Storage::path($ultimaVersion->ruta);
        $fileInfo = $getID3->analyze($filePath);

        // Agregar la información a la vista
        return view('livewire.archivo-item', [
            'archivo' => $archivo,
            'ultimaVersion' => $ultimaVersion,
            'bitrate' => $fileInfo['audio']['bitrate'] ?? null,
            'sample_rate' => $fileInfo['audio']['sample_rate'] ?? null,
            'lossless' => $fileInfo['audio']['lossless'] ?? null,
            'codec_name' => $fileInfo['audio']['codec_name'] ?? null,
            'duration' => $fileInfo['playtime_seconds'] ?? null,
            'filesize' => $fileInfo['filesize'] ?? null,
            'bits_per_sample' => $fileInfo['audio']['bits_per_sample'] ?? null,
        ]);
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
}
