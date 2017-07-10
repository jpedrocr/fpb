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
            'description' => 'Sénior',
        ]);
    }
}
