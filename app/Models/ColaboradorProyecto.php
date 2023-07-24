<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColaboradorProyecto extends Model
{
    use HasFactory;

    protected $table = 'colaborador_proyecto';

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'rol',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
