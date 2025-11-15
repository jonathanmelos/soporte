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

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }
}
