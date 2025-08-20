<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
        Notification::create([
            'user_id' => $order->user_id,
            'message' => 'New Order Created: ' . strtoupper($order->unique_id),
            'go_to_link' => route('admin.order.index'),
        ]);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('orders');  // Channel name
    }

    public function broadcastAs()
    {
        return 'order-created';  // Event name
    }
    /**
     * Override the `broadcastOn` method to bypass queue processing.
     */
    public function broadcastWith()
    {
        return [
            'order' => $this->order
        ];
    }

    public function shouldBroadcastNow(): bool
    {
        return true; // Forces the event to be broadcast immediately
    }
}
