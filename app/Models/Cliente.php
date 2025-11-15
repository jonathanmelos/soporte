<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    // Define el nombre de la tabla si no sigue el nombre por defecto (plural y en minúsculas)
    protected $table = 'clientes';

    // Define la clave primaria de la tabla si no es "id" o si tu clave primaria es diferente
    protected $primaryKey = 'id_cliente'; 

    public $timestamps = true; // Cambia a false si no tienes created_at y updated_at

    // Los campos que son asignables masivamente
    protected $fillable = [
        'empresa',
        'ruc',
        'contacto',
        'telefono',
        'correo',
        'web',
    ];

    // Relación con los requerimientos (asumiendo que un cliente tiene muchos requerimientos)
    public function requerimientos()
    {
        return $this->hasMany(Requerimientos::class, 'id_cliente', 'id_cliente');
    }
}

