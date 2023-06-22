<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Proyecto;

class ListaArchivo extends Component
{
    public $proyectoId;

    public function mount($proyectoId)
    {
        $this->proyectoId = $proyectoId;
    }

    public function render()
    {
        $proyecto = Proyecto::findOrFail($this->proyectoId);
        $archivos = $proyecto->archivos;

        return view('livewire.lista-archivo', compact('archivos'));
    }
}
