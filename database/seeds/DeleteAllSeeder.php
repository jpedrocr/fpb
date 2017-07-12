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
        DB::table('games')->delete();
        DB::table('phase_team')->delete();
        DB::table('competition_team')->delete();
        DB::table('teams')->delete();
        DB::table('clubs')->delete();
        DB::table('rounds')->delete();
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
