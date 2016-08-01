<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) Send Snatch`s log daily';

    /**
     * Create a new command instance.
     *
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
        //
        $title = "获取小说信息日志";

        Mail::raw($title, function($message) use($title) {
            $message->from('novel@dev.com', 'Novel');
            $message->to('275804511@qq.com');
            $message->subject($title);

            $filePath = [
                storage_path(). '/logs/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day.'.updateHot.log',
                storage_path(). '/logs/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day.'.initNovel.log',
                storage_path(). '/logs/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day.'.updateAll.log'
            ];
            foreach ($filePath as $path) {
                if(file_exists($path))
                    $message->attach($path);
            }
        });
    }
}
