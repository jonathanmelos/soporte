<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $id,
        public string $email,
        public string $code,
        public ?string $whatsapp,
        public string $requestedAt
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('admin-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'otp.requested';
    }

    public function broadcastWith(): array
    {
        return [
            'id'           => $this->id,
            'email'        => $this->email,
            'code'         => $this->code,
            'whatsapp'     => $this->whatsapp,
            'requested_at' => $this->requestedAt,
        ];
    }
}
