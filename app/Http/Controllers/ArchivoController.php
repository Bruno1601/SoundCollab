<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    public function subirArchivos(Request $request)
{
    $proyectoId = $request->input('proyecto_id');

    // Obtener el proyecto
    $proyecto = Proyecto::findOrFail($proyectoId);

    // Validar los archivos subidos si es necesario
    if ($request->hasFile('archivos')) {
        $archivos = $request->file('archivos');

        foreach ($archivos as $archivo) {
            // Obtener detalles del archivo
            $nombreArchivo = $archivo->getClientOriginalName();

            // Crear la ruta completa para la carpeta del archivo dentro de la carpeta del proyecto
            $rutaCarpetaArchivo = $proyecto->ruta_carpeta . '/' . $nombreArchivo;

            // Verificar si la carpeta del archivo ya existe, si no, crearla
            if (!Storage::exists('public/proyectos/' . $proyectoId . '/' . $nombreArchivo)) {
                Storage::makeDirectory('public/proyectos/' . $proyectoId . '/' . $nombreArchivo);
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

    // Redireccionar o realizar otras acciones después de guardar los archivos
    return redirect()->back()->with('success', 'Archivos subidos correctamente');
}
}
