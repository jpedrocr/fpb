<?php

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Club;
use App\Models\Category;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;
use App\Models\Competition;
use App\Models\Phase;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $team =
            Team::create([
                'club_id' => Club::where('fpb_id', 16)->first()->id,
                'category_id' => Category::where('fpb_id', 'equ')->first()->id,
                'fpb_id' => 27451,
                'name' => 'Aliança Sangalhos',
                'image' => 'http://www.fpb.pt/fpb_zone/sa/img/CLU/CLU_16_LOGO.gif',
                'agegroup_id' => Agegroup::where('description', 'Sénior')->first()->id,
                'competitionlevel_id' => Competitionlevel::where('description', 'Proliga')->first()->id,
                'season_id' => Season::where('current', true)->first()->id,
            ]);

        $team->competitions()->sync([
                Competition::where('fpb_id', 6171)->first()->id,
            ]);

        $team->phases()
            ->sync([
                Phase::where([
                        [ 'competition_id', '=', Competition::where('fpb_id', 6171)->first()->id ],
                        [ 'description', '=', '1ª Fase - Zona Norte' ],
                    ])->first()->id,
            ]);
    }
}
