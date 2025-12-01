<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'nombre',
        'lat',
        'lng',
        'radio_m',
    ];

    /**
     * Relación muchos-a-muchos con clientes
     * usando la tabla pivote cliente_ubicacion.
     */
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(
            cliente::class,      // modelo relacionado (así se llama en tu proyecto)
            'cliente_ubicacion', // tabla pivote
            'ubicacion_id',      // FK de esta tabla en la pivote
            'cliente_id'         // FK de cliente en la pivote
        );
    }
}
