<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('proyectos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proyectos.create');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto)
    {
        $this->authorize('update',$proyecto);

        return view('proyectos/edit',[
            'proyecto' => $proyecto
        ]);
    }

    public function verProyecto($proyecto)
    {
        $proyecto = Proyecto::findOrFail($proyecto);

        return view('proyectos.verproyecto', compact('proyecto'));
    }
}
