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

        // SET FOREIGN_KEY_CHECKS=0;
        // TRUNCATE TABLE games;
        // TRUNCATE TABLE phase_team;
        // TRUNCATE TABLE competition_team;
        // TRUNCATE TABLE teams;
        // TRUNCATE TABLE clubs;
        // TRUNCATE TABLE rounds;
        // TRUNCATE TABLE phases;
        // TRUNCATE TABLE competitions;
        // TRUNCATE TABLE associations;
        // TRUNCATE TABLE seasons;
        // TRUNCATE TABLE agegroups;
        // TRUNCATE TABLE competitionlevels;
        // TRUNCATE TABLE genders;
        // TRUNCATE TABLE categories;
        // SET FOREIGN_KEY_CHECKS=1;
    }
}
