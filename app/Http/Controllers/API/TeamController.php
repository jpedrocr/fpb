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
use App\Models\Competition;
use App\Models\Phase;

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
    public function getCompetitionsAndPhasesFromFPB($team_fpb_id)
    {
        $team = Team::where('fpb_id', $team_fpb_id)->first();

        $html = '';
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;317.104000;++ID('.$team_fpb_id.
            ')+CO(COMPETICOES)+BL(COMPETICOES);+MYBASEDIV(dEquipa_Ficha_Home_Comp);+RCNT(1000)+RINI(1)&');

        $crawler
            ->filterXPath('//div[contains(@class, "LinhaSeparadora01")]')
            ->each(function ($node) use ($team) {
                $competition_fpb_id = $node
                    ->nextAll()
                    ->eq(0)
                    ->filterXPath('//a[contains(@href, "!site.go?s=1&show=com&id=")]')
                    ->evaluate('substring-after(@href, "&id=")')[0];

                $competition_id = Competition::where('fpb_id', $competition_fpb_id)->first()->id;

                if ($team->competitions()->where('id', $competition_id)->count()==0) {
                    $team->competitions()->attach($competition_id);
                //     dump('Team:'.$team->id.'->Competition:'.$competition_id.' attached');
                // } else {
                //     dump('Team:'.$team->id.'->Competition:'.$competition_id.' already attached.');
                }

                $nextAll = $node->nextAll();
                $eq = 1;
                while (($eq<$nextAll->count()) and ($nextAll->eq($eq)->attr('class')=="Titulo04 TextoCor01")) {
                    $phase_description = trim(explode("\n", $nextAll->eq($eq)->text())[2]);

                    if ($team->phases()->where('description', $phase_description)->count()==0) {
                        $phase_id = Phase::where([
                                [ 'competition_id', '=', $competition_id ],
                                [ 'description', '=', $phase_description ],
                            ])->first()->id;
                        $team->phases()->attach($phase_id);
                    //     dump('Team:'.$team->id.'->Competition:'.$competition_id.'->Phase:'.$phase_description.' attached');
                    // } else {
                    //     dump('Team:'.$team->id.'->Competition:'.$competition_id.'->Phase:'.$phase_description.' already attached.');
                    }

                    $eq++;
                }
            });

        return $team->competitions()->count() . ' Competitions and ' . $team->phases()->count() . ' Phases';
    }
}
