<?php

use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\Association;
use App\Models\Category;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Club::create([
            'association_id' => Association::where('fpb_id', 3)->first()->id,
            'category_id' => Category::where('fpb_id', 'clu')->first()->id,
            'fpb_id' => 16,
            'name' => 'Sangalhos Desporto Clube',
            'image' => 'http://www.fpb.pt/fpb_zone/sa/img/CLU/CLU_16_LOGO.gif',
            'alternative_name' => 'Sangalhos DC',
            'founding_date' => '01/01/1940',
            'president' => 'António Jorge dos Santos Ribeiro',
            'address' => 'Rua Feliciano Godinho Neves, Sangalhos',
            'telephone' => '234741735',
            'fax_number' => '',
            'email' => 'sdc.geral@sapo.pt',
            'url' => 'http://www.sangalhosdc.pt',
            // 'venue_id' => 'Complexo Desportivo de Sangalhos',
        ]);
        Club::create([
            'association_id' => Association::where('fpb_id', 3)->first()->id,
            'category_id' => Category::where('fpb_id', 'clu')->first()->id,
            'fpb_id' => 18,
            'name' => 'Clube do Povo de Esgueira',
            'image' => 'http://www.fpb.pt/fpb_zone/sa/img/CLU/CLU_18_LOGO.gif',
            'alternative_name' => 'CP Esgueira',
            // 'founding_date' => '',
            // 'president' => '',
            // 'address' => '',
            // 'telephone' => '',
            // 'fax_number' => '',
            // 'email' => '',
            // 'url' => '',
            // 'venue_id' => '',
        ]);
    }
}
