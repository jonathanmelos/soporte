<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSessionScan extends Model
{
    use HasFactory;

    protected $table = 'work_session_scans';

    protected $fillable = [
        'uuid',
        'session_uuid',
        'project_code',
        'area',
        'description',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];
}
