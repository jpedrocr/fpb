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

class CompetitionController extends Controller
{
    public function index()
    {
        return Competition::all();
    }
    public function indexFromAssociationAndSeason($association_fpb_id, $season_fpb_id)
    {
        return Competition::where([
                [ 'association_id', '=', Association::where('fpb_id', $association_fpb_id)->first()->id ],
                [ 'season_id', '=', Season::where('fpb_id', $season_fpb_id)->first()->id ],
            ])
            ->get();
    }
    public function getFromFPB($association_fpb_id, $season_fpb_id)
    {
        $association_id = Association::where('fpb_id', $association_fpb_id)->first()->id;
        $season_id = Season::where('fpb_id', $season_fpb_id)->first()->id;

        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109030;++K_ID('.$association_fpb_id.
            ')+K_ID_EPOCA('.$season_fpb_id.')+CO(PROVAS)+BL(PROVAS)+MYBASEDIV(dAssProvas);+RCNT(100)+RINI(1)&');

        $crawler
            ->filterXPath('//a[contains(@href, "!site.go?s=1&show=com&id=")]')
            ->each(function ($node) use ($association_id, $season_id) {
                $fpb_id = $node->evaluate('substring-after(@href, "&id=")')[0];
                $name = $node->text();

                if (Competition::where('fpb_id', $fpb_id)->count()==0) {
                    // $html2 = '';
                    // $crawler2 = new Crawler();
                    // $crawler2->addHtmlContent($html2);

                    $client2 = new Client();
                    $crawler2 = $client2->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=com&id='.$fpb_id.
                        '&layout=calendario');

                    $node2 = $crawler2->filterXPath('//div[@class="COM_Header"]');

                    $image = $node2->filterXPath('//div/div[@id="Logo"]/img')->attr('src');
                    $competition_details = $node2->filterXPath('//div/div[@id="OutrosDados"]/strong');

                    if (Agegroup::where('description', $competition_details->eq(0)->text())->count()==0) {
                        $agegroup_id = Agegroup::create([
                            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
                            'description' => $competition_details->eq(0)->text(),
                        ])->id;
                    } else {
                        $agegroup_id = Agegroup::where('description', $competition_details->eq(0)->text())->first()->id;
                    }

                    if (Competitionlevel::where('description', $competition_details->eq(1)->text())->count()==0) {
                        $competitionlevel_id = Competitionlevel::create([
                            'gender_id' => Gender::where('fpb_id', '-')->first()->id,
                            'description' => $competition_details->eq(1)->text(),
                        ])->id;
                    } else {
                        $competitionlevel_id =
                            Competitionlevel::where('description', $competition_details->eq(1)->text())->first()->id;
                    }

                    Competition::create([
                        'association_id' => $association_id,
                        'category_id' => Category::where('fpb_id', 'com')->first()->id,
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

        return Competition::where([
                [ 'association_id', '=', $association_id ],
                [ 'season_id', '=', $season_id ],
            ])
            ->get();
    }
}
