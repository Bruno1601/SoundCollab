<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Proyecto;
use Illuminate\Support\Facades\File;

class CrearProyecto extends Component
{

    public $nombre_proyecto;
    public $descripcion;

    protected $rules = [
        'nombre_proyecto'=> 'required|string',
        'descripcion' => 'required|string'
    ];

    public function crearProyecto()
    {
        $datos = $this->validate();

        //Crear el directorio para el proyecto
        $rutaCarpeta = public_path('proyectos/' . uniqid()); // Genera un ID único para el directorio
        File::makeDirectory($rutaCarpeta);

        //Crear el proyecto
        Proyecto::create([
        'nombre_proyecto' => $datos['nombre_proyecto'],
        'descripcion' => $datos['descripcion'],
        'user_id' => auth()->user()->id,
        'ruta_carpeta' => $rutaCarpeta
        ]);

        //Crear un mensaje

        session()->flash('mensaje', 'El proyecto se creó correctamente');
        return redirect()->route('proyectos.index');




        //Redireccionar al usuario

    }

    public function render()
    {
        return view('livewire.crear-proyecto');
    }
}
