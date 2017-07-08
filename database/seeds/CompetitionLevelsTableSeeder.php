<?php

use Illuminate\Database\Seeder;
use App\Models\CompetitionLevel;
use App\Models\Gender;

class CompetitionLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompetitionLevel::create([
            'gender_id' => Gender::where('fpb_id','-')->first()->id,
            'description' => 'Proliga',
        ]);
    }
}
