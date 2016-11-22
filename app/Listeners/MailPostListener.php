<?php

namespace App\Listeners;

use App\Events\MailPostEvent;
use App\Models\Admin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailPostListener
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
     * @param  MailPostEvent  $event
     * @return void
     */
    public function handle(MailPostEvent $event)
    {
        $title = $event->title;
        $data = $event->data;
        $type = $event->type;
        \Mail::queue('emails.'.$type, ['data' => $data], function($message) use ($title) {
            $email = Admin::first()->email;
            $message->to($email)->subject($title);
        });
    }
}
