<?php

use Illuminate\Database\Seeder;
use App\Repositories\Snatch\Biquge;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Biquge::init();
        $novels = \App\Models\Novel::all();
        foreach($novels as $novel){
            Biquge::update($novel->id);
        }
    }
}
