<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Proyecto;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Livewire\WithFileUploads;


class ArchivoController extends Controller
{
    use WithFileUploads;

    public function subirArchivos(Request $request)
    {
        $proyectoId = $request->input('proyecto_id');

        $proyecto = Proyecto::findOrFail($proyectoId);

        if ($request->hasFile('archivos')) {
            $archivos = $request->file('archivos');
            $errores = [];

            foreach ($archivos as $archivo) {
                $nombreArchivoOriginal = $archivo->getClientOriginalName();
                $nombreArchivo = preg_replace('/[^A-Za-z0-9-_.]/', '', $nombreArchivoOriginal);
                $extension = $archivo->getClientOriginalExtension();

                $formatoEncontrado = $this->validarFormatoArchivo($extension);
                if (!$formatoEncontrado) {
                    $errores[] = 'Tipo de archivo no permitido: ' . $nombreArchivo;
                    continue;
                }

                $tamanioMaximo = 500 * 1024 * 1024;
                if ($archivo->getSize() > $tamanioMaximo) {
                    $errores[] = 'El archivo excede el tamaño máximo permitido: ' . $nombreArchivo;
                    continue;
                }

                $archivoExistente = Archivo::where('nombre', $nombreArchivo)
                    ->where('proyecto_id', $proyectoId)
                    ->first();

                if ($archivoExistente) {
                    $ultimaVersion = Version::where('archivo_id', $archivoExistente->id)
                        ->orderBy('version', 'desc')
                        ->first();

                    $numeroVersion = $ultimaVersion ? $ultimaVersion->version + 1 : 2;

                    $rutaCarpetaArchivo = storage_path("app/public/proyectos/{$proyectoId}/{$nombreArchivo}/{$numeroVersion}");

                    if (!Storage::exists($rutaCarpetaArchivo)) {
                        Storage::makeDirectory($rutaCarpetaArchivo);
                    }

                    $rutaArchivo = $archivo->storeAs("public/proyectos/{$proyectoId}/{$nombreArchivo}/{$numeroVersion}", $nombreArchivo);

                    $nuevaVersion = new Version();
                    $nuevaVersion->archivo_id = $archivoExistente->id;
                    $nuevaVersion->ruta = $rutaArchivo;
                    $nuevaVersion->usuario = auth()->user()->name;
                    $nuevaVersion->fecha_subida = now();
                    $nuevaVersion->version = $numeroVersion;
                    $nuevaVersion->save();
                } else {
                    $rutaCarpetaArchivo = storage_path("app/public/proyectos/{$proyectoId}/{$nombreArchivo}");

                    if (!Storage::exists($rutaCarpetaArchivo)) {
                        Storage::makeDirectory($rutaCarpetaArchivo);
                    }

                    $rutaArchivo = $archivo->storeAs("public/proyectos/{$proyectoId}/{$nombreArchivo}", $nombreArchivo);

                    $nuevoArchivo = new Archivo();
                    $nuevoArchivo->nombre = $nombreArchivo;
                    $nuevoArchivo->ruta = $rutaArchivo;
                    $nuevoArchivo->version = 1;
                    $nuevoArchivo->proyecto_id = $proyectoId;
                    $nuevoArchivo->save();

                    $nuevaVersion = new Version();
                    $nuevaVersion->archivo_id = $nuevoArchivo->id;
                    $nuevaVersion->ruta = $rutaArchivo;
                    $nuevaVersion->usuario = auth()->user()->name;
                    $nuevaVersion->fecha_subida = now();
                    $nuevaVersion->version = 1;
                    $nuevaVersion->save();
                }
            }

            if (count($errores) > 0) {
                return redirect()->back()->withErrors($errores)->withInput();
            }
        }

        return redirect()->back()->with('success', 'Archivos subidos correctamente');
    }



    private function obtenerNumeroVersion($nombreArchivo, $proyectoId)
    {
        $ultimoArchivo = Archivo::where('nombre', $nombreArchivo)
            ->where('proyecto_id', $proyectoId)
            ->latest('version')
            ->first();

        if ($ultimoArchivo) {
            return $ultimoArchivo->version + 1;
        } else {
            return 1;
        }
    }

    public function descargar($archivoId)
    {
        // Encuentra el archivo por su ID
        $archivo = Archivo::findOrFail($archivoId);

        // Encuentra la última versión del archivo
        $version = $archivo->versiones()->orderBy('version', 'desc')->first();

        // Verifica que la versión exista
        if ($version === null) {
            // Maneja el error como desees, aquí simplemente devolvemos un mensaje
            return response('No se encontró ninguna versión para este archivo.', 404);
        }

        // Devuelve la última versión del archivo para su descarga
        return Storage::download($version->ruta);
    }


    public function descargarVersion($versionId)
    {
        $version = Version::find($versionId);
        if (!$version) {
            abort(404, 'Versión no encontrada');
        }

        return Storage::download($version->ruta);
    }



    private function validarFormatoArchivo($extension)
    {
        $formatosPermitidos = [
            'pro_tools' => ['ptx', 'ptf', 'wav', 'aiff'],
            'logic_pro' => ['logicx', 'exs', 'wav', 'aiff'],
            'ableton_live' => ['als', 'alc', 'adv'],
            'cubase' => ['cpr', 'wav', 'aiff', 'vstpreset'],
            'fl_studio' => ['flp', 'wav', 'mp3', 'dll', 'vst3','rar','zip'],
            'studio_one' => ['song', 'wav', 'aiff', 'presence', 'multitrack'],
            'reason' => ['reason', 'cmb', 'sxt'],
            'bitwig_studio' => ['bwproject', 'bwt', 'bwpreset'],
            'reaper' => ['rpp', 'rpp-template', 'wav', 'aiff'],
            'nuendo' => ['npr', 'npv', 'wav', 'aiff'],
            'digital_performer' => ['dpdoc', 'wav', 'aiff', 'dppl', 'dpugp'],
            'fl_studio_old' => ['flp', 'wav', 'mp3', 'dll', 'vst'],
            'midi' => ['mid', 'midi'],
        ];

        foreach ($formatosPermitidos as $formato) {
            if (in_array($extension, $formato)) {
                return true;
            }
        }

        return false;
    }

    public function descargarCarpeta($proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);

        // Obtener la ruta de almacenamiento de los archivos del proyecto
        $carpetaProyecto = storage_path('app/public/proyectos/' . $proyecto->id);

        // Verificar si la carpeta del proyecto existe y contiene archivos
        if (!is_dir($carpetaProyecto) || !$this->carpetaContieneArchivos($carpetaProyecto)) {
            return redirect()->back()->withErrors(['El proyecto no tiene archivos para descargar']);
        }

        // Crear un archivo ZIP temporal
        $archivoZip = storage_path('app/public/proyectos/' . $proyecto->id . '.zip');
        $zip = new ZipArchive();

        if ($zip->open($archivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Agregar cada archivo de la carpeta al archivo ZIP
            $archivosCarpeta = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($carpetaProyecto));
            foreach ($archivosCarpeta as $archivo) {
                if (!$archivo->isDir()) {
                    $rutaArchivo = $archivo->getPathname(); // Obtener la ruta completa del archivo
                    $nombreArchivo = $archivo->getFilename(); // Obtener el nombre original del archivo
                    $zip->addFile($rutaArchivo, $nombreArchivo);
                }
            }

            // Cerrar el archivo ZIP
            $zip->close();

            // Descargar el archivo ZIP con el nombre del proyecto
            $nombreArchivoDescarga = $proyecto->nombre_proyecto . '.zip';
            return response()->download($archivoZip, $nombreArchivoDescarga)->deleteFileAfterSend(true);
        }

        // Si ocurre un error al crear el archivo ZIP, redirigir a la página anterior
        return redirect()->back()->withErrors(['No se pudo crear el archivo ZIP']);
    }

    private function limpiarNombreArchivo($nombreArchivo)
    {
        // Eliminar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = preg_replace('/\s+/', '_', $nombreArchivo); // Reemplazar espacios por guiones bajos (_)
        $nombreArchivo = preg_replace('/[^a-zA-Z0-9_.-]/', '', $nombreArchivo); // Eliminar caracteres especiales

        return $nombreArchivo;
    }

    private function carpetaContieneArchivos($carpeta)
    {
        $archivos = new \FilesystemIterator($carpeta);
        return $archivos->valid();
    }

    public function verProyecto($proyectoId)
{
    $proyecto = Proyecto::findOrFail($proyectoId);

    // Obtener todas las versiones de los archivos del proyecto
    $versiones = Version::whereHas('archivo', function ($query) use ($proyectoId) {
        $query->where('proyecto_id', $proyectoId);
    })->get();

    return view('proyectos.verproyecto', compact('proyecto', 'versiones'));
}


    public function reproducirArchivo($archivoId)
    {
        $archivo = Archivo::findOrFail($archivoId);

        if ($archivo->formato === 'mp3' || $archivo->formato === 'wav') {
            return response()->json(['url' => Storage::url($archivo->ruta)]);
        }

        return response()->json(['url' => null]);
    }

}
