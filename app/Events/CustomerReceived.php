<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customers;

    /**
     * Create a new event instance.
     */
    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Log::debug("Customers Received {$this->customers}");
        // Log::debug(json_encode($this->customers));
        

        return [
            new Channel('customers'),
        ];
    }

    // public function broadcastAs()
    // {
    //     return 'customers.received';
    // }
}
