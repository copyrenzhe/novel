<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Carbon\Carbon;
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
            $email = Admin::first()->email;
            $message->to($email);
            $message->subject($title);

            $filePath = [
                storage_path(). '/logs/update-'.Carbon::now()->toDateString(),
                storage_path(). '/logs/init-'.Carbon::now()->toDateString(),
                storage_path(). '/logs/repair-'.Carbon::now()->toDateString(),
                storage_path(). '/logs/sum-'.Carbon::now()->toDateString()
            ];
            foreach ($filePath as $path) {
                if(file_exists($path))
                    $message->attach($path);
            }
        });
    }
}
