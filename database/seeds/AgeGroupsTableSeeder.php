<?php

use Illuminate\Database\Seeder;
use App\Models\Agegroup;
use App\Models\Gender;

class AgegroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Masters/Veteranos',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sénior',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sub 20',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sub 19',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sub 18',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sub 16',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Sub 14',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', 'F')->first()->id,
            'description' => 'Sub 13 Fem',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', 'M')->first()->id,
            'description' => 'Sub 13 Masc',
        ]);
        Agegroup::create([
            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
            'description' => 'Mini 12',
        ]);
    }
}
