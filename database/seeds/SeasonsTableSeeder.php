<?php

use Illuminate\Database\Seeder;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seasons')->truncate();
        DB::table('seasons')->insert([
            'fpb_id' => 55,
            'start_year' => 2016,
            'end_year' => 2017,
            'current' => true,
        ]);
    }
}
