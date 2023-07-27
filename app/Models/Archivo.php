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


    /**
     * Obtener las versiones del archivo.
     */
    public function versiones()
    {
        return $this->hasMany(Version::class, 'archivo_id')->orderBy('version', 'desc');
    }
    /**
     * Obtener la última versión del archivo.
     */
    public function ultimaVersion()
    {
        return $this->hasOne(Version::class, 'archivo_id')->orderBy('version', 'desc');
    }

    public function bloqueadoPor()
    {
        return $this->belongsTo(User::class, 'bloqueado_por');
    }
}
