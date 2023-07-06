<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use Livewire\Component;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Storage;

class ListaArchivo extends Component
{
    public $proyectoId;

    public function render()
    {
        $proyecto = Proyecto::findOrFail($this->proyectoId);
        $archivos = $proyecto->archivos;

        foreach ($archivos as $archivo) {
            $nombreArchivo = $archivo->nombre;
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $archivo->formato = $extension;
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

        // Eliminar el archivo de almacenamiento
        Storage::delete($archivo->ruta);

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
