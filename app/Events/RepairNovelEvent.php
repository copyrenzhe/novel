<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Novel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RepairNovelEvent extends Event
{
    use SerializesModels;

    public $novel;
    /**
     * Create a new event instance.
     *
     * @param Novel $novel
     */
    public function __construct(Novel $novel)
    {
        $this->novel = $novel;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
