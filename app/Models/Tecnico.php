<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tecnico extends Model
{
    use HasFactory;

    protected $table = 'tecnicos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /**
     * Campos permitidos para asignación masiva.
     * Incluye todos los existentes + los nuevos.
     */
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

        // ➕ Campos añadidos recientemente
        'modalidad',          // afiliado | por horas
        'tarifa_hora_1',      // tarifa para jornada normal
        'tarifa_hora_2',      // tarifa para jornada adicional
        'tarifa_hora_3',      // tarifa para jornada extra
    ];

    /**
     * Relación: un técnico tiene muchas WorkSession
     */
    public function workSessions(): HasMany
    {
        return $this->hasMany(WorkSession::class, 'tecnico_id');
    }

    /**
     * Accessor que devuelve nombre completo.
     * Si no hay nombres/apellidos, usa el correo.
     */
    public function getNombreCompletoAttribute(): string
    {
        $nombre = trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? ''));

        if ($nombre !== '') {
            return $nombre;
        }

        // fallback por si no existen nombres o apellidos
        return $this->correo ?? '';
    }
}
