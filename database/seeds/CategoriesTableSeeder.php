<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'fpb_id' => 'ass',
            'name' => 'Associação',
        ]);
        Category::create([
            'fpb_id' => 'com',
            'name' => 'Competição',
        ]);
        Category::create([
            'fpb_id' => 'clu',
            'name' => 'Clube',
        ]);
        Category::create([
            'fpb_id' => 'equ',
            'name' => 'Equipa',
        ]);
        Category::create([
            'fpb_id' => 'tre',
            'name' => 'Treinador',
        ]);
        Category::create([
            'fpb_id' => 'ehu',
            'name' => 'Enquadramento Humano',
        ]);
        Category::create([
            'fpb_id' => 'atl',
            'name' => 'Atleta',
        ]);
        Category::create([
            'fpb_id' => 'jog',
            'name' => 'Jogo',
        ]);
        Category::create([
            'fpb_id' => 'recinto',
            'name' => 'Recinto',
        ]);
    }
}
