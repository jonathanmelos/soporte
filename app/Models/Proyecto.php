<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    // Nombre de la tabla (opcional si sigue el estándar)
    protected $table = 'proyecto'; 

    // Clave primaria personalizada
    protected $primaryKey = 'id_proyecto'; 

    // Indicar que esta tabla usa incrementos automáticos
    public $incrementing = true;

    // Definir los campos que son asignables masivamente
    protected $fillable = [
        'id_requerimiento',
        'autorizado',
        'responsable',
        'fecha_creacion',
        'fecha_entrega',
        'fecha_finalizacion',
        'precio',
        'documento',
        'avance_obra',
        'val_material',
        'val_equipo',
        'val_cliente',
        'val_planificacion',
        'estado',
    ];

    // Si la tabla no tiene timestamps, puedes deshabilitarlos
    public $timestamps = true; 

    // Relación con los requerimientos (asumiendo que un cliente tiene muchos requerimientos)
    public function requerimientos()
    {
        return $this->belongsTo(Requerimientos::class, 'id_requerimiento', 'id_requerimientos');
    }
    
    public function reportes()
    {
    return $this->hasMany(Reporte::class, 'id_proyecto'); // Ajusta 'proyecto_id' si tu clave foránea se llama distinto
    }
        public function chatreportes()
    {
        return $this->hasMany(Chatreporte::class, 'id_proyecto');
    }

}
