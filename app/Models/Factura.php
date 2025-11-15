<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'numero_autorizacion',
        'fecha_autorizacion',
        'ambiente',
        'clave_acceso',
        'ruc_emisor',
        'razon_social_emisor',
        'nombre_comercial_emisor',
        'ruc_comprador',
        'razon_social_comprador',
        'fecha_emision',
        'total_sin_impuestos',
        'total_descuento',
        'importe_total',
        'moneda',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class);
    }
}

