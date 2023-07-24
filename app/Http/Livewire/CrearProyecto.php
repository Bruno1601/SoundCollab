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
        'nombre_proyecto' => 'required|string',
        'descripcion' => 'required|string'
    ];

    public function crearProyecto()
    {
        $datos = $this->validate();

        // Crear el proyecto
        Proyecto::create([
            'nombre_proyecto' => $datos['nombre_proyecto'],
            'descripcion' => $datos['descripcion'],
            'user_id' => auth()->user()->id,
        ]);

        // Crear un mensaje
        session()->flash('mensaje', 'El proyecto se creó correctamente');
        return redirect()->route('proyectos.index');
    }

    public function render()
    {
        return view('livewire.crear-proyecto');
    }
}
