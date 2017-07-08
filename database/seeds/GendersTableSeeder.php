<?php

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::create([
            'fpb_id' => 'M',
            'description' => 'Masculino',
        ]);
        Gender::create([
            'fpb_id' => 'F',
            'description' => 'Feminino',
        ]);
        Gender::create([
            'fpb_id' => '-',
            'description' => 'Indiferente',
        ]);
    }
}
