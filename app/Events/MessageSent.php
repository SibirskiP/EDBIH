<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;



class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;

    /**
     * Kreira događaj za emitiranje.
     *
     * @param string $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;  // Poruka koju šaljemo
    }

    /**
     * Određuje na koji kanal se emitira događaj.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('chat.1');  // Kanal 'chat.1', ovo može biti bilo koji kanal
    }

    /**
     * Dodatni podaci koji se šalju sa događajem
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }
}
