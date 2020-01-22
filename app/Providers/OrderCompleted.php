<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use App\ModelAndRepository\Users\User;
use Illuminate\Queue\SerializesModels;
use App\ModelAndRepository\Orders\Order;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
     public $order;
     public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order,User $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
