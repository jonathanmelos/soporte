<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'whatsapp',
        'status',
        'requested_at',
        'shared_at',
        'shared_by_user_id',
        'notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'shared_at'    => 'datetime',
    ];

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeRecent(Builder $query, int $hours = 48): Builder
    {
        return $query->where('requested_at', '>=', now()->subHours($hours));
    }

    public function markAsShared(?int $userId = null, ?string $notes = null): void
    {
        $this->update([
            'status'           => 'shared',
            'shared_at'        => now(),
            'shared_by_user_id'=> $userId,
            'notes'            => $notes,
        ]);
    }

    public function markAsExpired(?string $notes = null): void
    {
        $this->update([
            'status' => 'expired',
            'notes'  => $notes ?? $this->notes,
        ]);
    }

    public function markAsVerified(?string $notes = null): void
    {
        $this->update([
            'status' => 'verified',
            'notes'  => $notes ?? $this->notes,
        ]);
    }
}
