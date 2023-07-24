<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use App\Models\Version;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ModalVersiones extends Component
{
    public $archivoId;
    public $versiones;

    public function mount($archivoId)
{
    $this->archivoId = $archivoId;

    $versiones = Version::where('archivo_id', $this->archivoId)->get();

    // Convertir la colección de versiones a un array
    $versiones = $versiones->map(function ($version) {
        return [
            'id' => $version->id, // Aquí está el ID de la versión
            'archivo' => [
                'nombre' => $version->archivo->nombre,
                'id' => $version->archivo->id
            ],
            'version' => $version->version,
            'usuario' => $version->usuario,
            'created_at' => $version->created_at,
            'archivo_id' => $version->archivo_id
        ];
    })->toArray();

    $this->versiones = $versiones;
}

public function eliminarArchivo($versionId)
{
    $version = Version::find($versionId);
    if($version) {
        // Asumiendo que tienes una columna 'ruta' en tu modelo Version que guarda la ruta del archivo
        $ruta = $version->ruta;

        // Asegúrate de que la ruta no sea null y de que el archivo exista antes de intentar eliminarlo
        if($ruta && Storage::exists($ruta)) {
            Storage::delete($ruta);

            // Obtenemos el directorio padre del archivo
            $directorioPadre = dirname($ruta);

            // Comprobamos que el directorio no tiene más archivos
            if (count(Storage::files($directorioPadre)) === 0) {
                // Si no hay más archivos en el directorio, lo eliminamos
                Storage::deleteDirectory($directorioPadre);
            }
        }

        // Ahora borramos la versión de la base de datos
        $version->delete();

        // Recarga los datos de las versiones después de eliminar la versión.
        $this->mount($this->archivoId);
    }
}


    public function cerrarModalVersiones()
    {

        $this->emit('cerrarModal');
        // dd('Cerrar modal'); // Verifica si se está llamando esta función
    }

    public function render()
    {
        return view('livewire.modal-versiones');
    }
}
