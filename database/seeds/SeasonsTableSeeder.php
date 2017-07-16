<?php

use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Season::getFromFPB();
        // Season::create([
        //     'fpb_id' => 55,
        //     'start_year' => 2016,
        //     'end_year' => 2017,
        //     'current' => true,
        // ]);
    }
}
