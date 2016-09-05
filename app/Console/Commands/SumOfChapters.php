<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Console\Command;

class SumOfChapters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sum:chapter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) Sum of chapter`s number for every novel';

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
        $this->info('----- STARTING THE PROCESS FOR SUM OF CHAPTER -----');
        $novels = Novel::all();
        foreach ($novels as $novel) {
            $novel->chapter_num = Chapter::where('novel_id', $novel->id)->count();
            $novel->save();
        }
        $this->info('----- FINISHED THE PROCESS FOR SUM OF CHAPTER -----');
    }
}
