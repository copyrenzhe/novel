<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MailPostEvent extends Event
{
    use SerializesModels;

    public $type;
    public $title;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $title, $data)
    {
        $this->type = $type;
        $this->title = $title;
        $this->data = $data;
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
