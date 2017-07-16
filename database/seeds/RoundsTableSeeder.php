<?php

use Illuminate\Database\Seeder;
use App\Models\Round;
use App\Models\Phase;

class RoundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Phase::getRoundsFromFPB(15045, 16);
        Phase::getRoundsFromFPB(15066, 16);
        Phase::getRoundsFromFPB(15088, 16);
        Phase::getRoundsFromFPB(15096, 16);
        Phase::getRoundsFromFPB(15105, 16);
        Phase::getRoundsFromFPB(15110, 16);
        Phase::getRoundsFromFPB(15113, 16);
        Phase::getRoundsFromFPB(15115, 16);
        Phase::getRoundsFromFPB(15116, 16);
        Phase::getRoundsFromFPB(15119, 16);
        Phase::getRoundsFromFPB(15411, 16);
        Phase::getRoundsFromFPB(15473, 16);
        Phase::getRoundsFromFPB(15474, 16);
        Phase::getRoundsFromFPB(15476, 16);
        Phase::getRoundsFromFPB(15479, 16);
        Phase::getRoundsFromFPB(15573, 16);
        Phase::getRoundsFromFPB(15804, 16);
        Phase::getRoundsFromFPB(15834, 16);
        Phase::getRoundsFromFPB(15841, 16);
        Phase::getRoundsFromFPB(15845, 16);
        Phase::getRoundsFromFPB(15950, 16);
        Phase::getRoundsFromFPB(15987, 16);
        Phase::getRoundsFromFPB(16037, 16);
        Phase::getRoundsFromFPB(16044, 16);
        Phase::getRoundsFromFPB(16088, 16);
        Phase::getRoundsFromFPB(16196, 16);
        Phase::getRoundsFromFPB(16350, 16);
        Phase::getRoundsFromFPB(16365, 16);
        Phase::getRoundsFromFPB(16378, 16);
        Phase::getRoundsFromFPB(16382, 16);
        Phase::getRoundsFromFPB(16480, 16);
        Phase::getRoundsFromFPB(16593, 16);
        Phase::getRoundsFromFPB(16756, 16);
        // Round::create([
        //     'phase_id' => Phase::where('fpb_id', 15045)->first()->id,
        //     'fpb_id' => 88236,
        //     'lap_number' => 1,
        //     'round_number' => 1,
        // ]);
    }
}
