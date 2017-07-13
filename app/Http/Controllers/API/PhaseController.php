<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Phase;
use App\Models\Competition;

use App\Http\Controllers\API\RoundController;

class PhaseController extends Controller
{
    public function index()
    {
        return Phase::all();
    }
    public static function updateOrCreateFromFPB($competition_fpb_id, $fpb_id, $description, $status)
    {
        return Phase::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'competition_id' =>
                    Competition::where('fpb_id', $competition_fpb_id)->first()->id,
                'description' =>
                    $description,
                'status' =>
                    $status,
            ]
        );
    }
    public function getRounds($phase_fpb_id)
    {
        return Phase::where('fpb_id', $phase_fpb_id)->first()
            ->rounds()
            ->get();
    }
    public function getRoundsFromFPB($phase_fpb_id, $club_fpb_id = null)
    {
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
                $description = explode(' ª volta', $descriptions->eq($i)->text());

                RoundController::updateOrCreateFromFPB(
                    $phase_fpb_id,
                    $fpb_ids->eq($i)->evaluate('substring-after(@id, "dJornada_")')[0],
                    trim($description[0]),
                    substr(trim(explode('ª jornada', $description[1])[0]), 8),
                    $club_fpb_id
                );
            }
        }

        return Phase::where('fpb_id', $phase_fpb_id)->first()
            ->rounds()
            ->get();
    }
}
