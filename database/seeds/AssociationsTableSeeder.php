<?php

use Illuminate\Database\Seeder;
use App\Models\Association;
use App\Models\Category;

class AssociationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Association::create([
            'category_id' => Category::where('fpb_id', 'ass')->first()->id,
            'fpb_id' => 50,
            'name' => 'Federação Portuguesa de Basquetebol',
            'image' => 'http://www.fpb.pt/fpb_zone/sa/img/ASS/ASS_50_LOGO.gif',
        ]);
        Association::create([
            'category_id' => Category::where('fpb_id', 'ass')->first()->id,
            'fpb_id' => 3,
            'name' => 'Associação de Basquetebol de Aveiro',
            'image' => 'http://www.fpb.pt/fpb_zone/sa/img/ASS/ASS_3_LOGO.jpg',
        ]);
    }
}
