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

        $teams = $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')->evaluate('substring-after(@href, "&id=")');
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
        $time = explode(":", str_replace('.',':',$game_details->eq(3)->text()));

        $results = $crawler->filterXPath('//div[@class="Centro"]//table//table/tr/td[@class="GameScoreFont01"]');

        $round_id = Round::where('fpb_id', $round_fpb_id)->first()->id;
        $category_id = Category::where('fpb_id', 'jog')->first()->id;
        $hometeam_id = Team::where('fpb_id', $hometeam_fpb_id)->first()->id;
        $outteam_id = Team::where('fpb_id', $outteam_fpb_id)->first()->id;
        $number = $game_details->eq(0)->text();
        $schedule = Carbon::create($date[2], $date[1], $date[0], $time[0], $time[1], 0, 'Europe/Lisbon');
        $home_result = $results->eq(0)->text();
        $out_result = $results->eq(1)->text();

        return Game::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'round_id' => $round_id,
                'category_id' => $category_id,
                'hometeam_id' => $hometeam_id,
                'outteam_id' => $outteam_id,
                'number' => $number,
                'schedule' => $schedule,
                'home_result' => $home_result,
                'out_result' => $out_result,
                'status' => $status,
            ]
        );
    }

    // public function indexFromRound($round_fpb_id)
    // {
    //     return Game::where('round_id', Round::where('fpb_id', $round_fpb_id)->first()->id)
    //         ->get();
    // }
    // public function getFromFPB($round_fpb_id)
    // {
    //     $round_id = Round::where('fpb_id', $round_fpb_id)->first()->id;
    //
    //     // $html = '';
    //     // $crawler = new Crawler();
    //     // $crawler->addHtmlContent($html);
    //
    //     $client = new Client();
    //     $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_JORNADA('.
    //         $round_fpb_id.')+CO(JOGOS)+BL(JOGOS)+MYBASEDIV(dJornada_'.
    //         $round_fpb_id.');+RCNT(10000)+RINI(1)&');
    //
    //     $crawler
    //         ->filterXPath('//div[contains(@class, "Tabela01")]/table/tr')
    //         ->each(function ($node) use ($round_id) {
    //             if ($node->filterXPath('//td')->eq(0)->text()!="Jogo") {
    //                 $tds = $node->filterXPath('//td');
    //
    //                 $fpb_id =
    //                     $tds->eq(0)->filterXPath('//a[contains(@href, "!site.go?s=1&show=jog&id=")]')
    //                         ->evaluate('substring-after(@href, "!site.go?s=1&show=jog&id=")')[0];
    //
    //                 if (Game::where('fpb_id', $fpb_id)->count()==0) {
    //
    //                     $category_id = Category::where('fpb_id', 'jog')->first()->id;
    //
    //                     $hometeam_fpb_id = $tds->eq(3)
    //                         ->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
    //                         ->evaluate('substring-after(@href, "!site.go?s=1&show=equ&id=")')[0];
    //                     $hometeam_id = Team::where('fpb_id', $hometeam_fpb_id)->first()->id;
    //
    //                     $outteam_fpb_id = $tds->eq(9)
    //                         ->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
    //                         ->evaluate('substring-after(@href, "!site.go?s=1&show=equ&id=")')[0];
    //                     $outteam_id = Team::where('fpb_id', $outteam_fpb_id)->first()->id;
    //
    //                     $number = trim($tds->eq(0)->text());
    //                     $home_result = trim($tds->eq(5)->text());
    //                     $out_result = trim($tds->eq(7)->text());
    //                     $status = trim($tds->eq(11)->text());
    //
    //                     Game::create([
    //                         'round_id' => $round_id,
    //                         'category_id' => $category_id,
    //                         'fpb_id' => $fpb_id,
    //                         'hometeam_id' => $hometeam_id,
    //                         'outteam_id' => $outteam_id,
    //                         'number' => $number,
    //                         'home_result' => $home_result,
    //                         'out_result' => $out_result,
    //                         'status' => $status,
    //                     ]);
    //
    //                 //     dump(
    //                 //         "Game ".$fpb_id.
    //                 //         "\n-> round_id:".$round_id.
    //                 //         "\n-> category_id:".$category_id.
    //                 //         "\n-> fpb_id:".$fpb_id.
    //                 //         "\n-> hometeam_id:".$hometeam_id.
    //                 //         "\n-> outteam_id:".$outteam_id.
    //                 //         "\n-> number:".$number.
    //                 //         "\n-> home_result:".$home_result.
    //                 //         "\n-> out_result:".$out_result.
    //                 //         "\n-> status:".$status.
    //                 //         "\n created"
    //                 //     );
    //                 // }
    //                 // else {
    //                 //     dump('Game '.$fpb_id.' exists');
    //                 }
    //             }
    //         });
    //     return Game::where('round_id', Round::where('fpb_id', $round_fpb_id)->first()->id)->get();
    // }
}
