<?php

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\Association;
use App\Models\Category;
use App\Models\AgeGroup;
use App\Models\CompetitionLevel;
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
        Competition::create([
            'association_id' => Association::where('fpb_id',50)->first()->id,
            'category_id' => Category::where('fpb_id','com')->first()->id,
            'fpb_id' => 6171,
            'name' => 'XIV Campeonato da Proliga',
            'image' => '/fpb_zone/sa/img/COM/COM_6171_LOGO.gif',
            'age_group_id' => AgeGroup::where('description','Sénior')->first()->id,
            'competition_level_id' => CompetitionLevel::where('description','Proliga')->first()->id,
            'season_id' => Season::where('fpb_id',55)->first()->id,
        ]);
    }
}
