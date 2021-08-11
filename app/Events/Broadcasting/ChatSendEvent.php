<?php

namespace App\Events\Broadcasting;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatSendEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $_message;
    public $_channel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $message, string $channel)
    {
        $this->_message = $message;
        $this->_channel = $channel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->_channel == 'staff') {
            return [
                new PrivateChannel('channel-customer'),
                new PrivateChannel('channel-staff')
            ];
        }

        return new PrivateChannel('channel-customer');
    }

    public function broadcastWith()
    {
        return $this->_message;
    }
}
