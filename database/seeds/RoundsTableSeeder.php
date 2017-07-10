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
        Round::create([
            'phase_id' => Phase::where('fpb_id', 15045)->first()->id,
            'fpb_id' => 88236,
            'lap_number' => 1,
            'round_number' => 1,
        ]);
    }
}
