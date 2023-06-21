<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Colaborador;


class ColaboradorProyecto extends Component
{
    public $modal = false;
    public $search = '';
    public $searchResults = [];
    public $proyectoId;
    public $ventanaCargaArchivos = false;

    public function limpiarBusqueda()
    {
        $this->search = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.colaborador-proyecto', [
            'ventanaCargaArchivos' => $this->ventanaCargaArchivos,
        ]);
    }

    public function crear()
    {
        $this->abrirModal();
    }

    public function abrirModal()
    {
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->limpiarBusqueda();
    }

    public function searchUsers()
    {
        if (empty($this->search)) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = User::where('name', 'LIKE', '%' . $this->search . '%')
            ->where('id', '!=', auth()->user()->id)
            ->get();
    }


    public function addColaborador($userId)
    {
        // Obtener el ID del proyecto actual del usuario autenticado
        $proyectoId = $this->proyectoId;

        // Verificar si el usuario ya es colaborador en el proyecto
        $existeColaborador = Colaborador::where('proyecto_id', $proyectoId)
            ->where('user_id', $userId)
            ->exists();

        if ($existeColaborador) {
            session()->flash('mensaje', 'Ya es un colaborador');
            return;

        }

        // Guardar el colaborador en la base de datos
        Colaborador::create([
            'proyecto_id' => $proyectoId,
            'user_id' => $userId,
        ]);

         // Restablecer los valores y cerrar el modal
        $this->limpiarBusqueda();
        session()->flash('mensajeExito', 'Colaborador agregado exitosamente.');
    }

    public function abrirVentanaCargaArchivos()
    {
        $this->ventanaCargaArchivos = true;
    }



}


