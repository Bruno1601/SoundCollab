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
    public $ultimaVersion; // Agregamos esta propiedad para almacenar la última versión

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

        $versiones = $versiones->map(function ($version) use ($getID3) {
            $filePathVersion = Storage::path($version->ruta);
            $fileInfoVersion = $getID3->analyze($filePathVersion);
            $bitrate = $fileInfoVersion['audio']['bitrate'] ?? null;
            $sample_rate = $fileInfoVersion['audio']['sample_rate'] ?? null;
            $duration = $fileInfoVersion['playtime_seconds'] ?? null; // Obtener la duración
            $filesize = $fileInfoVersion['filesize'] ?? null; // Obtener el tamaño del archivo

            return [
                'id' => $version->id,
                'archivo' => [
                    'nombre' => $version->archivo->nombre,
                    'id' => $version->archivo->id,
                    'bloqueado' => $version->archivo->bloqueado,
                    'bloqueadoPor' => $version->archivo->bloqueadoPor,
                ],
                'version' => $version->version,
                'usuario' => $version->usuario,
                'created_at' => $version->created_at,
                'archivo_id' => $version->archivo_id,
                'bitrate' => $bitrate,
                'sample_rate' => $sample_rate,
                'duration' => $duration, // Agregar la duración
                'filesize' => $filesize, // Agregar el tamaño del archivo
            ];
        })->toArray();

        // Obtenemos la última versión del archivo
        $this->ultimaVersion = $this->archivo->ultimaVersion;

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
