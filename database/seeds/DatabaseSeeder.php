<?php

use Illuminate\Database\Seeder;

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
        $this->call(NovelTableSeeder::class);

        $this->call(OverNovelSeeder::class);

        $this->call(ContinueNovelSeeder::class);

        $this->call(RepairNovelSeeder::class);

        $this->call(MingzhuNovelsInitSeeder::class);
    }
}
