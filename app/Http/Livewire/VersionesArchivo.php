<?php

namespace App\Http\Livewire;

use App\Models\Archivo;
use App\Models\Version;
use Livewire\Component;

class VersionesArchivo extends Component
{
    public $modal = false;
    public $archivoId;

    protected $listeners = ['cerrarModal'];

    public function abrirModalVersiones()
    {
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function render()
{
    $versiones = Version::where('archivo_id', $this->archivoId)->get();

    $versiones = $versiones->map(function ($version) {
        return [
            'archivo' => [
                'nombre' => $version->archivo->nombre,
                'id' => $version->archivo->id
            ],
            'version' => $version->version,
            'usuario' => $version->usuario,
            'created_at' => $version->created_at,
            'archivo_id' => $version->archivo_id
        ];
    })->toArray();

    return view('livewire.versiones-archivo', ['versiones' => $versiones]);
}

}
