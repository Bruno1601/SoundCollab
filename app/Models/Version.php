<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    protected $table = 'versiones';

    protected $fillable = ['archivo_id', 'ruta', 'usuario', 'fecha_subida', 'version'];


    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Aquí puedes definir cualquier otra relación según tus necesidades
}
