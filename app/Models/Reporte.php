<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    // Nombre de la tabla (opcional si sigue el estándar)
    protected $table = 'reporte'; 

    // Clave primaria personalizada
    protected $primaryKey = 'id_reporte'; 

    // Indicar que esta tabla usa incrementos automáticos
    public $incrementing = true;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_reporte',
        'id_proyecto',
        'fecha',
        'tareas',
        'tecnicos',
        'material',
        'herramienta',
        'novedades',
        'foto1',
        'foto2',
        'foto3',
        
    ];

    // Relación con el modelo Proyecto (relación inversa)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto'); // La clave foránea en la tabla de reportes
    }
}


