<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;

abstract class Job
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "onQueue" and "delay" queue helper methods.
    |
    */

    use Queueable, InteractsWithQueue;

    public function touch()
    {
        if(method_exists($this->job, 'getPheanstalk')) {
            $this->job->getPheanstalk()->touch($this->job->getPheanstalkJob());
        }
    }
}
