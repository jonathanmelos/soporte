<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'tecnico_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'device_session_uuid',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    /**
     * Relación: una jornada pertenece a un técnico
     */
    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }

    /**
     * Relación: una jornada tiene muchos registros de ubicación
     */
    public function locations()
    {
        return $this->hasMany(WorkSessionLocation::class, 'work_session_id');
    }
}
