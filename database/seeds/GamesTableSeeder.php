<?php

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Round;
use App\Models\Category;
use App\Models\Team;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Game::create([
            'round_id' => Round::where('fpb_id', 88241)->first()->id,
            'category_id' => Category::where('fpb_id', 'jog')->first()->id,
            'fpb_id' => 216293,
            'hometeam_id' => Team::where('fpb_id', 27451)->first()->id,
            'outteam_id' => Team::where('fpb_id', 27448)->first()->id,
            'number' => 164,
            'home_result' => 40,
            'out_result' => 67,
            'status' => 'Oficial',
        ]);
    }
}
