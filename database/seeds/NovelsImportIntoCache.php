<?php

use App\Models\Novel;
use Illuminate\Database\Seeder;

class NovelsImportIntoCache extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $novels = Novel::all();
        foreach ($novels as $novel) {
            \Redis::zAdd(config('cache.redis.view_total'), $novel->hot, $novel->id);
        }
    }
}
