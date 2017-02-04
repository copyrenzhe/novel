<?php

namespace App\Console\Commands;

use App\Events\Event;
use App\Events\MailPostEvent;
use Illuminate\Console\Command;

class SiteMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) Monitor the site`s status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = config('url');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode===200){
            session(['siteMonitor' => 0]);
        } else {
            $error_times = session('siteMonitor') + 1;
            session(['siteMonitor' => $error_times]);
        }
        //连续一个小时访问异常
        if(session('siteMonitor') > 5){
            Event::fire(new MailPostEvent('system', '网站访问异常', array()));
            session(['siteMonitor' => 0]);
        }
    }
}
