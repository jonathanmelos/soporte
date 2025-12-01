<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkSessionLocation extends Model
{
    use HasFactory;

    protected $table = 'work_session_locations';

    protected $fillable = [
        'work_session_id',
        'tecnico_id',
        'lat',
        'lng',
        'accuracy',
        'recorded_at',
        'event_type',
        'device_session_uuid',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * Relación con la sesión principal
     * work_session_locations → work_sessions
     */
    public function session()
    {
        return $this->belongsTo(WorkSession::class, 'work_session_id');
    }

    /**
     * Relación con el técnico
     * Esto permite acceder a $location->tecnico->nombres
     */
    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }
}
