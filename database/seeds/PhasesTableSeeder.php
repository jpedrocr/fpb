<?php

use Illuminate\Database\Seeder;

use App\Models\Phase;
use App\Models\Competition;
use App\Models\Team;

class PhasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::getCompetitionsAndPhasesFromFPB(27451);
        Team::getCompetitionsAndPhasesFromFPB(27477);
        Team::getCompetitionsAndPhasesFromFPB(27478);
        Team::getCompetitionsAndPhasesFromFPB(28186);
        Team::getCompetitionsAndPhasesFromFPB(28191);
        Team::getCompetitionsAndPhasesFromFPB(28204);
        Team::getCompetitionsAndPhasesFromFPB(28217);
        Team::getCompetitionsAndPhasesFromFPB(28232);
        Team::getCompetitionsAndPhasesFromFPB(28247);
        Team::getCompetitionsAndPhasesFromFPB(30239);
        // Phase::create([
        //     'competition_id' => Competition::where('fpb_id', 6171)->first()->id,
        //     'fpb_id' => 15045,
        //     'description' => '1ª Fase - Zona Norte',
        //     'status' => 'Terminado'
        // ]);
    }
}
