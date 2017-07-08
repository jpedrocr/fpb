<?php

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\Phase;

class PhasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Phase::create([
            'competition_id' => Competition::where('fpb_id','6171')->first()->id,
            'fpb_id' => 15045,
            'description' => '1ª Fase - Zona Norte',
            'status' => 'Terminado'
        ]);
    }
}
