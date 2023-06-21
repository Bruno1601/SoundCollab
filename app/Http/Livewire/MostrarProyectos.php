<?php

namespace App\Http\Livewire;

use App\Models\Proyecto;
use Livewire\Component;


class MostrarProyectos extends Component
{
    protected $listeners = ['eliminarProyecto'];

    public function eliminarProyecto(Proyecto $proyecto)
    {
        $proyecto->delete();
    }

    public function render()
    {
        $proyectos = Proyecto::where('user_id', auth()->user()->id)->paginate(4);
        return view('livewire.mostrar-proyectos',[
            'proyectos'=> $proyectos
        ]);

    }
}
