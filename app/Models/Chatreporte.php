<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chatreporte extends Model
{
    // Nombre de la tabla
    protected $table = 'chatreportes';

    // Clave primaria personalizada
    protected $primaryKey = 'id_chat';

    // Indicar si la clave primaria es autoincremental
    public $incrementing = true;

    // El tipo de clave primaria
    protected $keyType = 'int';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_proyecto',
        'fecha',
        'texto',
        'foto1',
        'usuario',
    ];

    // Relación: un chatreporte pertenece a un proyecto
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}

