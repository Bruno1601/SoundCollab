<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre_proyecto',
        'descripcion',
        'user_id',
        'ruta_carpeta'
    ];

    public function users(){
        return $this->belongsToMany(User::class,'colaborador_proyecto');
    }

    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
}
