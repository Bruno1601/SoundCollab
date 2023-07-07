<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Livewire\WithFileUploads;

class ArchivoController extends Controller
{
    use WithFileUploads;

    public function subirArchivos(Request $request)
{
    $proyectoId = $request->input('proyecto_id');

    // Obtener el proyecto
    $proyecto = Proyecto::findOrFail($proyectoId);

    // Validar los archivos subidos si es necesario
    if ($request->hasFile('archivos')) {
        $archivos = $request->file('archivos');
        $errores = [];

        foreach ($archivos as $archivo) {
            // Obtener detalles del archivo
            $nombreArchivo = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();

            // Validar el tipo de archivo
            $formatoEncontrado = $this->validarFormatoArchivo($extension);
            if (!$formatoEncontrado) {
                $errores[] = 'Tipo de archivo no permitido: ' . $nombreArchivo;
            }

            // Validar el tamaño del archivo
            $tamanioMaximo = 500 * 1024 * 1024; // 500 MB en bytes
            if ($archivo->getSize() > $tamanioMaximo) {
                $errores[] = 'El archivo excede el tamaño máximo permitido: ' . $nombreArchivo;
            }

            if (count($errores) === 0) {
                // Crear la ruta completa para la carpeta del archivo dentro de la carpeta del proyecto
                $rutaCarpetaArchivo = storage_path('app/public/proyectos/' . $proyectoId . '/' . $nombreArchivo);

                // Verificar si la carpeta del archivo ya existe, si no, crearla
                if (!Storage::exists($rutaCarpetaArchivo)) {
                    Storage::makeDirectory($rutaCarpetaArchivo);
                }

                // Guardar el archivo en el almacenamiento dentro de la carpeta del archivo
                $rutaArchivo = $archivo->storeAs('public/proyectos/' . $proyectoId . '/' . $nombreArchivo, $nombreArchivo);

                // Crear una nueva instancia del modelo Archivo
                $nuevoArchivo = new Archivo();
                $nuevoArchivo->nombre = $nombreArchivo;
                $nuevoArchivo->ruta = $rutaArchivo;
                $nuevoArchivo->proyecto_id = $proyectoId;

                // Guardar el archivo en la base de datos
                $nuevoArchivo->save();
            }
        }

        if (count($errores) > 0) {
            return redirect()->back()->withErrors($errores)->withInput();
        }
    }

    return redirect()->back()->with('success', 'Archivos subidos correctamente');
}


    public function descargar($archivoId)
    {
        $archivo = Archivo::findOrFail($archivoId);

        return response()->download(storage_path('app/' . $archivo->ruta));
    }

    private function validarFormatoArchivo($extension)
    {
        $formatosPermitidos = [
            'pro_tools' => ['ptx', 'ptf', 'wav', 'aiff'],
            'logic_pro' => ['logicx', 'exs', 'wav', 'aiff'],
            'ableton_live' => ['als', 'alc', 'adv'],
            'cubase' => ['cpr', 'wav', 'aiff', 'vstpreset'],
            'fl_studio' => ['flp', 'wav', 'mp3', 'dll', 'vst3','rar'],
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
    $archivos = Archivo::where('proyecto_id', $proyectoId)->get();

    // Obtener la extensión del archivo y asignarla como formato
    foreach ($archivos as $archivo) {
        $nombreArchivo = $archivo->nombre;
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $archivo->formato = $extension;
    }

    return view('proyectos.verproyecto', compact('proyecto', 'archivos'));
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
