<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Chapter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RepairChapterEvent extends Event
{
    use SerializesModels;
    public $chapter;

    /**
     * Create a new event instance.
     *
     * @param Chapter $chapter
     */
    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
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
