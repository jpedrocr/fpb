<?php

use Illuminate\Database\Seeder;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->truncate();
        DB::table('genders')->insert([
            'fpb_id' => 'M',
            'description' => 'Masculino',
        ]);
        DB::table('genders')->insert([
            'fpb_id' => 'F',
            'description' => 'Feminino',
        ]);
        DB::table('genders')->insert([
            'fpb_id' => '-',
            'description' => 'Indiferente',
        ]);
    }
}
