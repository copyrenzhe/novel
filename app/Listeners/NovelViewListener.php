<?php

namespace App\Listeners;

use App\Events\NovelView;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovelViewListener
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
     * @param  NovelView  $event
     * @return void
     */
    public function handle(NovelView $event)
    {
        $chapter = $event->chapter;
        $chapter->views = $chapter->views + 1;
        $chapter->increment('views');
        $chapter->novel()->increment('hot');
    }
}
