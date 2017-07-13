<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

use App\Models\Game;
use App\Models\Round;
use App\Models\Category;
use App\Models\Team;

use App\Http\Controllers\API\TeamController;

class GameController extends Controller
{
    public function index()
    {
        return Game::all();
    }
    public static function updateOrCreateFromFPB($round_fpb_id, $fpb_id, $status)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=jog&id='.$fpb_id);

        $teams = $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
            ->evaluate('substring-after(@href, "&id=")');
        $hometeam_fpb_id = $teams[0];
        $outteam_fpb_id = $teams[1];

        if (Team::where('fpb_id', $hometeam_fpb_id)->count()==0) {
            TeamController::updateOrCreateFromFPB($hometeam_fpb_id);
        }
        if (Team::where('fpb_id', $outteam_fpb_id)->count()==0) {
            TeamController::updateOrCreateFromFPB($outteam_fpb_id);
        }

        $game_details = $crawler->filterXPath('//table[@class="JOG_Infox"]/tr/td');
        $date = explode("/", $game_details->eq(2)->text());
        $time = explode(":", str_replace('.', ':', $game_details->eq(3)->text()));

        $results = $crawler->filterXPath('//div[@class="Centro"]//table//table/tr/td[@class="GameScoreFont01"]');

        return Game::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'round_id' =>
                    Round::where('fpb_id', $round_fpb_id)->first()->id,
                'category_id' =>
                    Category::where('fpb_id', 'jog')->first()->id,
                'hometeam_id' =>
                    Team::where('fpb_id', $hometeam_fpb_id)->first()->id,
                'outteam_id' =>
                    Team::where('fpb_id', $outteam_fpb_id)->first()->id,
                'number' =>
                    $game_details->eq(0)->text(),
                'schedule' =>
                    Carbon::create($date[2], $date[1], $date[0], $time[0], $time[1], 0, 'Europe/Lisbon'),
                'home_result' =>
                    $results->eq(0)->text(),
                'out_result' =>
                    $out_result,
                'status' =>
                    $results->eq(1)->text(),
            ]
        );
    }
}
