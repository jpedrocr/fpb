<?php

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\Association;
use App\Models\Category;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;

class CompetitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Association::getCompetitionsFromFPB(50, 55);
        Association::getCompetitionsFromFPB(3, 55);
        // Competition::create([
        //     'association_id' => Association::where('fpb_id', 50)->first()->id,
        //     'category_id' => Category::where('fpb_id', 'com')->first()->id,
        //     'fpb_id' => 6171,
        //     'name' => 'XIV Campeonato da Proliga',
        //     'image' => '/fpb_zone/sa/img/COM/COM_6171_LOGO.gif',
        //     'agegroup_id' => Agegroup::where('description', 'Sénior')->first()->id,
        //     'competitionlevel_id' => Competitionlevel::where('description', 'Proliga')->first()->id,
        //     'season_id' => Season::where('fpb_id', 55)->first()->id,
        // ]);
    }
}
