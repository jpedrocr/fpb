<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Team;
use App\Models\Club;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;

class TeamController extends Controller
{
    public function index()
    {
        return Team::all();
    }
    public function indexFromClubAndSeason($club_fpb_id, $season_fpb_id)
    {
        return Team::where([
                [ 'club_id', '=', Club::where('fpb_id', $club_fpb_id)->first()->id ],
                [ 'season_id', '=', Season::where('fpb_id', $season_fpb_id)->first()->id ],
            ])
            ->get();
    }
    public function getFromFPB($club_fpb_id)
    {
        $club_id = Club::where('fpb_id', $club_fpb_id)->first()->id;
        $season_id = Season::where('current', true)->first()->id;

        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.105010;++K_ID_CLUBE('.$club_fpb_id.
            ')+CO(EQUIPAS)+BL(EQUIPAS-02);+MYBASEDIV(dClube_Ficha_Home_Equipas);+RCNT(1000)+RINI(1)&');

        $crawler
            ->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
            ->each(function ($node) use ($club_id, $season_id) {
                $fpb_id = $node->evaluate('substring-after(@href, "&id=")')[0];
                $name = $node->text();

                if (Team::where('fpb_id', $fpb_id)->count()==0) {
                    // $html2 = '';
                    // $crawler2 = new Crawler();
                    // $crawler2->addHtmlContent($html2);

                    $client2 = new Client();
                    $crawler2 = $client2->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=equ&id='.$fpb_id);

                    $node2 = $crawler2->filterXPath('//div[@class="Equipa_Header"]');

                    $image = $node2->filterXPath('//div/div[@id="Logo"]/img')->attr('src');
                    $club_details = $node2->filterXPath('//div/span[@class="Info"]');

                    if (Agegroup::where('description', $club_details->eq(0)->text())->count()==0) {
                        $agegroup_id = Agegroup::create([
                            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
                            'description' => $club_details->eq(0)->text(),
                        ])->id;
                    } else {
                        $agegroup_id = Agegroup::where('description', $club_details->eq(0)->text())->first()->id;
                    }

                    if (Competitionlevel::where('description', $club_details->eq(1)->text())->count()==0) {
                        $competitionlevel_id = Competitionlevel::create([
                            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
                            'description' => $club_details->eq(1)->text(),
                        ])->id;
                    } else {
                        $competitionlevel_id =
                            Competitionlevel::where('description', $club_details->eq(1)->text())->first()->id;
                    }

                    Team::create([
                        'club_id' => $club_id,
                        'category_id' => Category::where('fpb_id', 'equ')->first()->id,
                        'fpb_id' => $fpb_id,
                        'name' => $name,
                        'image' => $image,
                        'agegroup_id' => $agegroup_id,
                        'competitionlevel_id' => $competitionlevel_id,
                        'season_id' => $season_id
                    ]);
                // } else {
                //     dump('Competition '.$fpb_id.'->'.$name.' exists');
                }
            });

        return Team::where([
                [ 'club_id', '=', $club_id ],
                [ 'season_id', '=', $season_id ],
            ])
            ->get();
    }
}
