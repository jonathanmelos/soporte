<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados'; // nombre de la tabla (opcional si sigue el estándar)
    protected $primaryKey = 'id_personal'; // clave primaria personalizada
    
}
