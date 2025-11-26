<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkSessionPhoto extends Model
{
    use HasFactory;

    protected $table = 'work_session_photos';

    protected $fillable = [
        'work_session_id',
        'type',              // 'selfie_inicio', 'foto_contexto', etc.
        'photo_path',        // storage/app/public/jornadas/xxxxx.jpg
    ];

    protected $casts = [
        'work_session_id' => 'integer',
        'type'            => 'string',
        'photo_path'      => 'string',
    ];

    /**
     * Relación inversa:
     * Una foto pertenece a UNA sesión de trabajo
     */
    public function session()
    {
        return $this->belongsTo(WorkSession::class, 'work_session_id');
    }
}
