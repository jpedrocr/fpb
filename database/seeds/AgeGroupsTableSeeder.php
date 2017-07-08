<?php

use Illuminate\Database\Seeder;
use App\Models\AgeGroup;
use App\Models\Gender;

class AgeGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AgeGroup::create([
            'gender_id' => Gender::where('fpb_id','-')->first()->id,
            'description' => 'Sénior',
        ]);
    }
}
