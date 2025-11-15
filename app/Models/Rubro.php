<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    // Nombre de la tabla (si deseas especificarlo)
    // protected $table = 'rubros'; // Descomenta si el nombre no es 'rubros'

    // Clave primaria personalizada (si aplica)
    // protected $primaryKey = 'id_rubro'; // Descomenta si usas otra clave primaria

    public $incrementing = true; // Asume autoincremento

    public $timestamps = true; // Usa created_at y updated_at

    protected $fillable = [
        'nombre',
        'unidad_medida',
        'creado',
        'categoria',
    ];

    // Puedes añadir relaciones si Rubro pertenece a otro modelo, por ejemplo:
    // public function requerimiento()
    // {
    //     return $this->belongsTo(Requerimientos::class, 'id_requerimientos', 'id_requerimientos');
    // }
}

