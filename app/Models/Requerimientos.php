<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimientos extends Model
{
    protected $table = 'requerimientos'; // Nombre de la tabla (si es diferente)
    
    protected $primaryKey = 'id_requerimientos'; // Clave primaria de la tabla

    public $incrementing = true; // Si usas autoincremento

    public $timestamps = true; // Si no usas created_at y updated_at, ponlo en false

    protected $fillable = [
        'id_cliente',
        'contacto',
        'creado',
        'trabajo',
        'proridad',
        'ejecucion',
        'pago',
        'estado',
        'comentarios',
        'observaciones',
    ];

    // Relación inversa (un requerimiento pertenece a un cliente)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // Relación uno a muchos (Un requerimiento tiene muchos rubros)
    public function rubros()
    {
        return $this->hasMany(RubroRequerimiento::class, 'id_requerimientos', 'id_requerimientos');
    }

    // Si se requiere mostrar un nombre con formato (ejemplo)
    public function getFormattedPriorityAttribute()
    {
        $priority_map = [
            'alta' => 'Alta',
            'media' => 'Media',
            'baja' => 'Baja',
        ];
        return $priority_map[$this->proridad] ?? 'No definido';
    }
}
