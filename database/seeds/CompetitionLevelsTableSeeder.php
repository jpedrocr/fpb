<?php

use Illuminate\Database\Seeder;
use App\Models\Competitionlevel;
use App\Models\Gender;

class CompetitionlevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id','-')->first()->id,
            'description' => 'Proliga',
        ]);
    }
}
