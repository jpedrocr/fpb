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

use App\Http\Controllers\API\CompetitionController;
use App\Http\Controllers\API\ClubController;

class TeamController extends Controller
{
    public function index()
    {
        return Team::all();
    }
    public static function updateOrCreateFromFPB($fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=equ&id='.$fpb_id);

        $node = $crawler->filterXPath('//div[@class="Equipa_Header"]');
        $club_details = $node->filterXPath('//div/span[@class="Info"]');

        $club_fpb_id = $node->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]')
            ->evaluate('substring-after(@href, "&id=")')[0];

        if (Club::where('fpb_id', $club_fpb_id)->count()==0) {
            ClubController::updateOrCreateFromFPB($club_fpb_id);
        }

        return Team::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'club_id' =>
                    Club::where('fpb_id', $club_fpb_id)->first()->id,
                'category_id' =>
                    Category::where('fpb_id', 'equ')->first()->id,
                'name' =>
                    trim($node->filterXPath('//div[@id="NomeClube"]')->text()),
                'image' =>
                    $node->filterXPath('//div[@id="Logo"]/img')->attr('src'),
                'season_id' =>
                    Season::where('current', true)->first()->id,
                'agegroup_id' =>
                    Agegroup::firstOrCreate(
                        ['description' => $club_details->eq(0)->text()],
                        ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                    )->id,
                'competitionlevel_id' =>
                    Competitionlevel::firstOrCreate(
                        ['description' => $club_details->eq(1)->text()],
                        ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                    )->id,
            ]
        );
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
            ->each(
                function ($node) use ($team) {
                    $competition_fpb_id = $node
                        ->nextAll()
                        ->eq(0)
                        ->filterXPath('//a[contains(@href, "!site.go?s=1&show=com&id=")]')
                        ->evaluate('substring-after(@href, "&id=")')[0];

                    $competition = Competition::where('fpb_id', $competition_fpb_id)->first();

                    if ($competition->phases()->count()==0) {
                        CompetitionController::getPhasesFromFPB($competition_fpb_id);
                    }

                    if ($team->competitions()->where('id', $competition->id)->count()==0) {
                        $team->competitions()->attach($competition->id);
                    }

                    $nextAll = $node->nextAll();
                    $eq = 1;
                    while (($eq<$nextAll->count()) and ($nextAll->eq($eq)->attr('class')=="Titulo04 TextoCor01")) {
                        $phase_description = trim(explode("\n", $nextAll->eq($eq)->text())[2]);

                        if ($team->phases()->where('description', $phase_description)->count()==0) {
                            $phase_id = Phase::where([
                                    [ 'competition_id', '=', $competition->id ],
                                    [ 'description', '=', $phase_description ],
                                ])->first()->id;
                            $team->phases()->attach($phase_id);
                        }

                        $eq++;
                    }
                }
            );

        return Team::where('fpb_id', $team_fpb_id)
            ->with('competitions', 'phases')
            ->first();
    }
    public function getSeasonTeams($club_fpb_id, $season_fpb_id)
    {
        return Team::where([
                [ 'club_id', '=', Club::where('fpb_id', $club_fpb_id)->first()->id ],
                [ 'season_id', '=', Season::where('fpb_id', $season_fpb_id)->first()->id ],
            ])
            ->get();
    }
}
