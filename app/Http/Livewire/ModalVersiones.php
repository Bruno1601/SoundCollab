<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use App\Models\Version;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use getID3 as GlobalGetID3;

class ModalVersiones extends Component
{
    public $archivoId;
    public $versiones;
    public $archivo;

    public function mount($archivoId)
    {
        $this->archivoId = $archivoId;

        // Obtener el archivo relacionado con la versión
        $this->archivo = Archivo::find($this->archivoId);

        if (!$this->archivo) {
            // Manejar el caso si el archivo no se encuentra
            // Puedes mostrar un mensaje de error o redirigir a otra página, según tus necesidades.
            return;
        }

        // Obtener las versiones relacionadas con el archivo
        $versiones = Version::where('archivo_id', $this->archivoId)->get();

        // Crear una nueva instancia de getID3
        $getID3 = new GlobalGetID3;

        // Convertir la colección de versiones a un array
        $versiones = $versiones->map(function ($version) use ($getID3) {
            // Obtener la ruta del archivo de la versión
            $filePathVersion = Storage::path($version->ruta);

            // Analizar el archivo de la versión
            $fileInfoVersion = $getID3->analyze($filePathVersion);

            // Extraer la información deseada del archivo
            $bitrate = $fileInfoVersion['audio']['bitrate'] ?? null;
            $sample_rate = $fileInfoVersion['audio']['sample_rate'] ?? null;
            // etc...

            return [
                'id' => $version->id, // Aquí está el ID de la versión
                'archivo' => [
                    'nombre' => $version->archivo->nombre,
                    'id' => $version->archivo->id,
                    'bloqueado' => $version->archivo->bloqueado, // Añadir la información de bloqueo del archivo
                    'bloqueadoPor' => $version->archivo->bloqueadoPor, // Añadir la información de quién bloqueó el archivo
                ],
                'version' => $version->version,
                'usuario' => $version->usuario,
                'created_at' => $version->created_at,
                'archivo_id' => $version->archivo_id,
                'bitrate' => $bitrate,
                'sample_rate' => $sample_rate,
                // etc...
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
