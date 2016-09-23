<?php

namespace App\Listeners;

use App\Events\RepairChapterEvent;
use App\Jobs\ChapterRepair;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RepairChapterListener
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
     * @param  RepairChapter  $event
     * @return void
     */
    public function handle(RepairChapterEvent $event)
    {
        //
        $chapter = $event->chapter;
        dispatch(new ChapterRepair($chapter));
    }
}
