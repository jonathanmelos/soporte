<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RubroRequerimiento extends Model
{
    // Nombre de la tabla (en plural por convención de Laravel)
    protected $table = 'rubros_requerimientos';

    // Clave primaria
    protected $primaryKey = 'id_rubros'; // Cambié el nombre a 'id_rubros' como lo solicitaste

    // Indicar que el campo de clave primaria es autoincremental
    public $incrementing = true;

    // Si no usas las columnas created_at y updated_at, ponerlo en false
    public $timestamps = true;

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'requerimiento_id',   // Relación con la tabla 'requerimientos'
        'nombre_rubro',       // Nombre del rubro
        'unidad',             // Unidad de medida
        'cantidad',           // Cantidad del rubro
        'nota',               // Nota adicional
        'archivo',            // Archivo (foto o video)
    ];

    /**
     * Relación inversa con el modelo Requerimientos.
     * Un RubroRequerimiento pertenece a un Requerimiento.
     */
    public function requerimiento()
    {
        return $this->belongsTo(Requerimientos::class, 'requerimiento_id', 'id_requerimientos');
    }
}
