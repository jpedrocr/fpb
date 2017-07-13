<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Round;
use App\Models\Phase;

use App\Http\Controllers\API\GameController;

class RoundController extends Controller
{
    public function index()
    {
        return Round::all();
    }
    public static function updateOrCreateFromFPB($phase_fpb_id, $fpb_id, $lap_number, $round_number)
    {
        return Round::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'phase_id' =>
                    Phase::where('fpb_id', $phase_fpb_id)->first()->id,
                'lap_number' =>
                    $lap_number,
                'round_number' =>
                    $round_number,
            ]
        );
    }
    public function getGamesFromFPB($round_fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_JORNADA('.
            $round_fpb_id.')+CO(JOGOS)+BL(JOGOS)+MYBASEDIV(dJornada_'.
            $round_fpb_id.');+RCNT(10000)+RINI(1)&');

        $crawler->filterXPath('//div[contains(@class, "Tabela01")]/table/tr')
            ->each(
                function ($node) use ($round_fpb_id) {
                    $tds = $node->filterXPath('//td');
                    if ($tds->eq(0)->text()!="Jogo") {
                        GameController::updateOrCreateFromFPB(
                            $round_fpb_id,
                            $tds->eq(0)->filterXPath('//a[contains(@href, "!site.go?s=1&show=jog&id=")]')
                            ->evaluate('substring-after(@href, "!site.go?s=1&show=jog&id=")')[0],
                            trim($tds->eq(11)->text())
                        );
                    }
                }
            );

        return Round::where('fpb_id', $round_fpb_id)->first()
            ->games()
            ->get();
    }
}
