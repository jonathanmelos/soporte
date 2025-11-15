<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cedula',
        'nombres',
        'apellidos',
        'telefono',
        'correo',
        'direccion',
        'tipo_sangre',
        'contacto_emergencia',
        'foto_perfil',
        'perfil_completo',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'tecnico_especialidad');
    }

    public function zonas()
    {
        return $this->belongsToMany(Zona::class, 'tecnico_zona');
    }

    public function documentos()
    {
        return $this->hasMany(TecnicoDocumento::class);
    }

    public function workSessions()
    {
        return $this->hasMany(WorkSession::class);
    }
}
