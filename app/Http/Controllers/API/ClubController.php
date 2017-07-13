<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Club;
use App\Models\Association;
use App\Models\Category;
use App\Models\Season;

use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\AssociationController;

class ClubController extends Controller
{
    public function index()
    {
        return Club::all();
    }
    public static function updateOrCreateFromFPB($fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html2);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=clu&id='.$fpb_id);

        $club_details = $crawler->filterXPath('//table[@class="TabelaHor01"]/tr/td');

        $original_address = explode("<br>", trim($club_details->eq(2)->html()));
        $address1 = trim($original_address[0]);
        $address2 = trim($original_address[1]);

        $association_fpb_id = $club_details->eq(3)->filterXPath('//a')->evaluate('substring-after(@href, "&id=")')[0];

        if (Association::where('fpb_id', $association_fpb_id)->count()==0) {
            AssociationController::updateOrCreateFromFPB($association_fpb_id);
        }

        $association_id = Association::where('fpb_id', $association_fpb_id)->first()->id;
        $category_id = Category::where('fpb_id', 'clu')->first()->id;
        $name = $crawler->filterXPath('//div/div[@id="NomeClube"]')->text();
        // $alternative_name = $alternative_name;
        $image = $crawler->filterXPath('//div/div[@id="Logo"]/img')->attr('src');
        $founding_date = trim($club_details->eq(0)->text());
        $president = trim($club_details->eq(1)->text());
        $address = implode("\n", $original_address);
        $telephone = trim($club_details->eq(5)->text());
        $fax_number = trim($club_details->eq(6)->text());
        $email = trim($club_details->eq(7)->text());
        $url = trim($club_details->eq(8)->text());
        // $venue_id = Venue::where('name', trim($club_details->eq(4)->text()))->first()->id;

        return Club::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'association_id' => $association_id,
                'category_id' => $category_id,
                'name' => $name,
                // 'alternative_name' => $alternative_name,
                'image' => $image,
                'founding_date' => $founding_date,
                'president' => $president,
                'address' => $address,
                'telephone' => $telephone,
                'fax_number' => $fax_number,
                'email' => $email,
                'url' => $url,
                // 'venue_id' => $venue_id,
            ]
        );
    }

    public function getTeams($club_fpb_id)
    {
        return Club::where('fpb_id', $club_fpb_id)->first()
            ->teams()->where('season_id', Season::where('current', true)->first()->id)
            ->get();
    }
    public function getSeasonTeams($club_fpb_id, $season_fpb_id)
    {
        return Club::where('fpb_id', $club_fpb_id)->first()
        ->teams()->where('season_id', Season::where('fpb_id', $season_fpb_id)->first()->id)
        ->get();
    }
    public function getTeamsFromFPB($club_fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.105010;++K_ID_CLUBE('
            .$club_fpb_id.')+CO(EQUIPAS)+BL(EQUIPAS-02);+MYBASEDIV(dClube_Ficha_Home_Equipas);+RCNT(1000)+RINI(1)&');

        $crawler
            ->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
            ->each(function ($node) {
                TeamController::updateOrCreateFromFPB(
                    $node->evaluate('substring-after(@href, "&id=")')[0]
                );
            });

        return Club::where('fpb_id', $club_fpb_id)->first()
            ->teams()->where('season_id', Season::where('current', true)->first()->id)
            ->get();
    }
}
