<?php

namespace App\Http\Livewire;

use App\Models\Proyecto;
use Livewire\Component;

class EditarProyecto extends Component
{
    public $proyecto_id;
    public $nombre_proyecto;
    public $descripcion;

    protected $rules = [
        'nombre_proyecto'=> 'required|string',
        'descripcion' => 'required|string'
    ];


    public function mount(Proyecto $proyecto)
    {
        $this->proyecto_id = $proyecto->id;
        $this->nombre_proyecto = $proyecto->nombre_proyecto;
        $this->descripcion = $proyecto->descripcion;
    }

    public function editarProyecto(){
        $datos=$this->validate();

        //encontrar el proyecto a editar
        $proyecto = Proyecto::find($this->proyecto_id);
        //asignar los valores
        $proyecto->nombre_proyecto = $datos['nombre_proyecto'];
        $proyecto->descripcion = $datos['descripcion'];
        //guardar el proyecto
        $proyecto->save();
        //redireccionar
        session()->flash('mensaje','El proyecto se actualizó correctamente');
        return redirect()->route('proyectos.index');
    }
    public function render()
    {
        return view('livewire.editar-proyecto');
    }
}
