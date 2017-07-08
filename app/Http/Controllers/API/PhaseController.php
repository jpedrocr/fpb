<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Phase;
use App\Models\Competition;

class PhaseController extends Controller
{
    public function index()
    {
        return Phase::all();
    }
    public function indexFromCompetition($competition_fpb_id)
    {
        return Phase::where('competition_id', Competition::where('fpb_id',$competition_fpb_id)->first()->id)
            ->get();
    }
    public function getFromFPB($competition_fpb_id)
    {
        $competition_id = Competition::where('fpb_id',$competition_fpb_id)->first()->id;

        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO('.$competition_fpb_id.')+CO(FASES)+BL(FASES)+MYBASEDIV(dCompFases);+RCNT(10000)+RINI(1)&');

        $crawler
            ->filterXPath('//div[contains(@style, "margin:10px;")]')
            ->each(function ($node) use ($competition_id) {
                $fpb_id = $node->filterXPath('//div[contains(@id, "dFase_")]')->evaluate('substring-after(@id, "dFase_")')[0];
                $description = $node->filterXPath('//div[contains(@class, "Titulo01")]')->text();
                $status = explode("\n",$node->text())[3];
                if (Phase::where('fpb_id',$fpb_id)->count()==0)
                {
                    Phase::create([
                        'competition_id' => $competition_id,
                        'fpb_id' => $fpb_id,
                        'description' => $description,
                        'status' => $status,
                    ]);
                // }
                // else {
                //     dump('Competition '.$fpb_id.'->'.$name.' exists');
                }
            });
        return Phase::where('competition_id', Competition::where('fpb_id',$competition_fpb_id)->first()->id)->get();
    }
}
