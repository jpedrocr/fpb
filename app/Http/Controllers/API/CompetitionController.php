<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Competition;
use App\Models\Association;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;

use App\Http\Controllers\API\PhaseController;

class CompetitionController extends Controller
{
    public function index()
    {
        return Competition::all();
    }
    public static function updateOrCreateFromFPB($association_fpb_id, $fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=com&id='.$fpb_id);

        $node = $crawler->filterXPath('//div[@class="COM_Header"]');

        $competition_details = $node->filterXPath('//div/div[@id="OutrosDados"]/strong');
        $description = explode("/", $competition_details->eq(2)->text());
        $start_year = $description[0];
        $end_year = $description[1];

        return Competition::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'association_id' =>
                    Association::where('fpb_id', $association_fpb_id)->first()->id,
                'category_id' =>
                    Category::firstOrCreate(['fpb_id' => 'com'])->id,
                'name' =>
                    trim($node->filterXPath('//div/div[@id="Nome"]')->text()),
                'image' =>
                    $node->filterXPath('//div/div[@id="Logo"]/img')->attr('src'),
                'agegroup_id' =>
                    Agegroup::firstOrCreate(
                        ['description' => $competition_details->eq(0)->text()],
                        ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                    )->id,
                'competitionlevel_id' =>
                    Competitionlevel::firstOrCreate(
                        ['description' => $competition_details->eq(1)->text()],
                        ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                    )->id,
                'season_id' =>
                    Season::where([
                        ['start_year', '=', $start_year],
                        ['end_year', '=', $end_year],
                    ])->first()->id,
            ]
        );
    }

    public function getPhases($competition_fpb_id)
    {
        return Competition::where('fpb_id', $competition_fpb_id)->first()
            ->phases()
            ->get();
    }
    public static function getPhasesFromFPB($competition_fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO('.
            $competition_fpb_id.')+CO(FASES)+BL(FASES)+MYBASEDIV(dCompFases);+RCNT(10000)+RINI(1)&');

        $crawler->filterXPath('//div[contains(@style, "margin:10px;")]')
            ->each(
                function ($node) use ($competition_fpb_id) {
                    PhaseController::updateOrCreateFromFPB(
                        $competition_fpb_id,
                        $node->filterXPath('//div[contains(@id, "dFase_")]')
                            ->evaluate('substring-after(@id, "dFase_")')[0],
                        $node->filterXPath('//div[contains(@class, "Titulo01")]')->text(),
                        explode("\n", $node->text())[3]
                    );
                }
            );

        return Competition::where('fpb_id', $competition_fpb_id)->first()
            ->phases()
            ->get();
    }
}
