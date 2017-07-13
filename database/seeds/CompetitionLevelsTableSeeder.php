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
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sel Nac',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Sen M',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'LPB',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Liga Fem',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'CN 1.ª Divisão',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => '1Div F',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Sen F',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => '2Div F',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Proliga',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Sub 19 F',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Sub 18 M',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Sub 16 F',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sel Reg',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Sub 16 M',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Sub 14 F',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Sub 14 M',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Mini 12 M',
        ]);
        Competitionlevel::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Mini 12 F',
        ]);
    }
}
