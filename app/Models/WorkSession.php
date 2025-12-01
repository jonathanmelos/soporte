<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkSession extends Model
{
    use HasFactory;

    protected $table = 'work_sessions';

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
        'uuid',
        'device_uuid',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    /**
     * Relación: una jornada pertenece a un técnico.
     */
    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }

    /**
     * Relación: una jornada tiene muchos registros de ubicación.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(WorkSessionLocation::class, 'work_session_id');
    }
}
