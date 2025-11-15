<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    // Campos que se pueden asignar en masa
    protected $fillable = [
        'factura_id',
        'codigo',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'descuento',
        'precio_total_sin_impuesto',
        'impuesto_valor',
    ];

    /**
     * Relación con la factura (cada detalle pertenece a una factura)
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}


