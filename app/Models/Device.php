<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_uuid',
        'platform',
        'brand',
        'model',
        'os_version',
        'app_version',
        'last_seen_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
