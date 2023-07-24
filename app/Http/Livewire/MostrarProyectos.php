<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Storage;


class MostrarProyectos extends Component
{
    protected $listeners = ['eliminarProyecto'];

    public function eliminarProyecto($proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);

        // Elimina todos los archivos y directorios relacionados en el servidor
        Storage::deleteDirectory('public/proyectos/' . $proyectoId);

        // Luego elimina el proyecto de la base de datos
        $proyecto->delete();

        // Envía un mensaje a la vista para mostrar el éxito de la eliminación del proyecto
        session()->flash('success', 'Proyecto eliminado correctamente');
    }

    public function render()
{
    $proyectos = Proyecto::where('user_id', auth()->user()->id)
        ->orWhereHas('users', function ($query) {
            $query->where('users.id', auth()->user()->id);
        })
        ->orderByDesc('created_at')
        ->paginate(4);

    return view('livewire.mostrar-proyectos', [
        'proyectos' => $proyectos
    ]);
}
}
