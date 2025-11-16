<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    // 👇 forzamos el nombre correcto de la tabla
    protected $table = 'especialidades';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function tecnicos()
    {
        return $this->belongsToMany(
            Tecnico::class,
            'tecnico_especialidad',
            'especialidad_id',
            'tecnico_id',
        );
    }
}
