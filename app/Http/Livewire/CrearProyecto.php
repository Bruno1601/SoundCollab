<?php

namespace App\Http\Livewire;

use Livewire\Component;

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

    }

    public function render()
    {
        return view('livewire.crear-proyecto');
    }
}
