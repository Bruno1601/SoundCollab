<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

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
        return view('livewire.archivo-item');

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
