<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatLocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scheduleId;
    public $seatNumber;
    public $isLocked;

    /**
     * Create a new event instance.
     */
    public function __construct($scheduleId, $seatNumber, $isLocked = true)
    {
        $this->scheduleId = $scheduleId;
        $this->seatNumber = $seatNumber;
        $this->isLocked = $isLocked;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('schedule.' . $this->scheduleId),
        ];
    }
    
    public function broadcastAs()
    {
        return 'seat.locked';
    }
}
