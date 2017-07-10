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
        DB::table('phases')->delete();
        DB::table('competitions')->delete();
        DB::table('associations')->delete();
        DB::table('seasons')->delete();
        DB::table('agegroups')->delete();
        DB::table('competitionlevels')->delete();
        DB::table('genders')->delete();
        DB::table('categories')->delete();
    }
}
