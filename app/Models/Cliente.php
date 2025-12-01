<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    // PK real de tu tabla
    protected $primaryKey = 'id_cliente';

    public $timestamps = true;

    protected $fillable = [
        'empresa',
        'ruc',
        'contacto',
        'telefono',
        'correo',
        'web',
    ];

    /**
     * Relación: un cliente tiene muchos requerimientos
     */
    public function requerimientos(): HasMany
    {
        return $this->hasMany(Requerimientos::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Relación muchos-a-muchos: un cliente puede tener varias ubicaciones.
     * Pivot: cliente_ubicacion
     */
    public function ubicaciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Ubicacion::class,      // modelo relacionado
            'cliente_ubicacion',   // tabla pivote
            'cliente_id',          // FK en pivote que apunta a clientes.id_cliente
            'ubicacion_id'         // FK en pivote que apunta a ubicaciones.id
        );
    }
}
