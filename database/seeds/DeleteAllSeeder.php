<?php

use Illuminate\Database\Seeder;


class DeleteAllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('associations')->delete();
        DB::table('seasons')->delete();
        DB::table('age_groups')->delete();
        DB::table('genders')->delete();
        DB::table('categories')->delete();
    }
}
