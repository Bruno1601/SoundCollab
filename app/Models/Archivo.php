<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = ['proyecto_id', 'nombre', 'ruta', 'version'];

     /**
     * Obtener el proyecto al que pertenece el archivo.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class,'proyecto_id');
    }
}
