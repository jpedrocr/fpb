<?php

use Illuminate\Database\Seeder;
use App\Models\Phase;
use App\Models\Competition;

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
            'competition_id' => Competition::where('fpb_id', 6171)->first()->id,
            'fpb_id' => 15045,
            'description' => '1ª Fase - Zona Norte',
            'status' => 'Terminado'
        ]);
    }
}
