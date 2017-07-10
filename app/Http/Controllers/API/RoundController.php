<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Round;
use App\Models\Phase;

class RoundController extends Controller
{
    public function index()
    {
        return Round::all();
    }
    public function indexFromPhase($phase_fpb_id)
    {
        return Round::where('phase_id', Phase::where('fpb_id', $phase_fpb_id)->first()->id)
            ->get();
    }
    public function getFromFPB($phase_fpb_id)
    {
        $phase_id = Phase::where('fpb_id', $phase_fpb_id)->first()->id;

        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_FASE('.
            $phase_fpb_id.')+CO(JORNADAS)+BL(JORNADAS)+MYBASEDIV(dFase_'.
            $phase_fpb_id.');+RCNT(100000)+RINI(1)&');

        $fpb_ids = $crawler->filterXPath('//div[contains(@id, "dJornada_")]');
        $descriptions = $crawler->filterXPath('//div[contains(@class, "Titulo03")]');

        if ($fpb_ids->count() == $descriptions->count()) {
            for ($i=0; $i < $fpb_ids->count(); $i++) {
                $fpb_id = $fpb_ids->eq($i)->evaluate('substring-after(@id, "dJornada_")')[0];
                $description =
                    explode(
                        ' ª volta',
                        $descriptions->eq($i)->text()
                    );
                $lap_number =
                    trim(
                        $description[0]
                    );
                $round_number =
                    substr(
                        trim(
                            explode(
                                'ª jornada',
                                $description[1]
                            )[0]
                        ),
                        8
                    );
                if (Round::where('fpb_id', $fpb_id)->count()==0) {
                    Round::create([
                        'phase_id' => $phase_id,
                        'fpb_id' => $fpb_id,
                        'lap_number' => $lap_number,
                        'round_number' => $round_number,
                    ]);
                //     dump('Round '.$fpb_id.'->'.$lap_number.'->'.$round_number.' created');
                // } else {
                //     dump('Round '.$fpb_id.'->'.$lap_number.'->'.$round_number.' exists');
                }
            }
        }
        return Round::where('phase_id', $phase_id)->get();
    }
}
