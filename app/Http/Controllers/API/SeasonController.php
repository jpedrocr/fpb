<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Season;

class SeasonController extends Controller
{
    public function index()
    {
        return Season::all();
    }
    public function getFromFPB()
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.60100;++BL(B1)+CO(B1)+K_ID(10004)+MYBASEDIV(dShowCompeticoes);+RCNT(10)+RINI(1)&');

        $crawler
            ->filter('option')
            ->reduce(function (Crawler $node) {
                return !($node->text() == "(Época)");
            })
            ->each(function ($node) {
                if (Season::where('fpb_id',$node->attr('value'))->count()==0) {
                    $description = explode('/',$node->text());
                    $start_year = $description[0];
                    $end_year = $description[1];
                    Season::create([
                        'fpb_id' => $node->attr('value'),
                        'start_year' => $start_year,
                        'end_year' => $end_year,
                        'current' => ($node->attr('selected')!=null),
                    ]);
                //     dump($node->attr('value').'->'.$description[0].'/'.$description[1].' created');
                // }
                // else {
                //     dump($node->attr('value').'->'.$node->text().' exists');
                }
            });
        return Season::all();
    }
}
