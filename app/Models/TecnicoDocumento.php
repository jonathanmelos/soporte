<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TecnicoDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'tecnico_id',
        'tipo',
        'archivo_url',
        'descripcion',
        'fecha_emision',
        'fecha_expiracion',
    ];

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }
}
