<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NovelView' => [
            'App\Listeners\NovelViewListener',
        ],
        'App\Events\RepairChapterEvent' => [
            'App\Listeners\RepairChapterListener',
        ],
        'App\Events\RepairNovelEvent' => [
            'App\Listeners\RepairNovelListener',
        ],
        'App\Events\MailPostEvent' => [
            'App\Listeners\MailPostListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
