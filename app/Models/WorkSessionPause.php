<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSessionPause extends Model
{
    use HasFactory;

    protected $table = 'work_session_pauses';

    protected $fillable = [
        'uuid',
        'session_uuid',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];
}
