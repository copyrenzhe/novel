<?php

namespace App\Listeners;

use App\Events\RepairNovelEvent;
use App\Jobs\SnatchChapters;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RepairNovelListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RepairNovelEvent  $event
     * @return void
     */
    public function handle(RepairNovelEvent $event)
    {
        dispatch(new SnatchChapters($event->novel->id));
    }
}
