<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    public function __construct(string $message)
    {
        \Log::info('Broadcasting JobPosted: ' . $message);
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        \Log::info('Broadcasting on chat-channel');
        return ['chat-channel'];
    }

    public function broadcastAs(): string
    {
        return 'chat';
    }
}
