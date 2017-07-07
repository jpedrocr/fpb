<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        DB::table('categories')->insert([
            'fpb_id' => 'ass',
            'name' => 'Associação',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'com',
            'name' => 'Competição',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'clu',
            'name' => 'Clube',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'equ',
            'name' => 'Equipa',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'tre',
            'name' => 'Treinador',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'ehu',
            'name' => 'Enquadramento Humano',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'atl',
            'name' => 'Atleta',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'jog',
            'name' => 'Jogo',
        ]);
        DB::table('categories')->insert([
            'fpb_id' => 'recinto',
            'name' => 'Recinto',
        ]);
    }
}
