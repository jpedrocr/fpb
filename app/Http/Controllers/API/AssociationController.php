<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Association;
use App\Models\Category;
use App\Models\Season;

use App\Http\Controllers\API\CompetitionController;
use App\Http\Controllers\API\ClubController;

class AssociationController extends Controller
{
    public function index()
    {
        return Association::all();
    }
    public function getFromFPB()
    {
        AssociationController::updateOrCreateFromFPB(50);

        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109050;++BL(B1)+CO(B1)+'.
            'MYBASEDIV(dShowAssociacoes);+RCNT(10)+RINI(1)&');

        $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=ass&id=")]')
            ->each(
                function ($node) {
                    AssociationController::updateOrCreateFromFPB(
                        $node->evaluate('substring-after(@href, "&id=")')[0]
                    );
                }
            );

        return Association::all();
    }
    public static function updateOrCreateFromFPB($fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=ass&id='.$fpb_id);

        $content = $crawler->filterXPath('//div[@id="dConteudosx"]');

        $association_details = $content->filterXPath('//div/table[@class="TabelaHor01"]/tr/td');

        $original_address = explode("<br>", trim($association_details->eq(3)->html()));
        $address1 = trim($original_address[0]);
        $address2 = trim($original_address[1]);
        $category_id = Category::firstOrCreate(['fpb_id' => 'ass'])->id;
        $name = trim($content->filterXPath('//div/div[@class="Assoc_FichaHeader_Nome"]/div')->text());
        $image = $content->filterXPath('//div/div[@class="Assoc_FichaHeader_Foto"]/img')->attr('src');
        $president = trim($association_details->eq(0)->text());
        $technical_director = trim($association_details->eq(1)->text());
        $cad_president = trim($association_details->eq(2)->text());
        $address = implode("\n", $original_address);
        $telephone = trim($association_details->eq(4)->text());
        $fax_number = trim($association_details->eq(5)->text());
        $email = trim($association_details->eq(6)->filterXPath('//a')
            ->evaluate('substring-after(@href, "mailto:")')[0]);
        $url = trim($association_details->eq(7)->filterXPath('//a')->attr('href'));

        return Association::updateOrCreate(
            [
                'fpb_id' => $fpb_id
            ],
            [
                'category_id' => $category_id,
                'name' => $name,
                'image' => $image,
                'president' => $president,
                'technical_director' => $technical_director,
                'cad_president' => $cad_president,
                'address' => $address,
                'telephone' => $telephone,
                'fax_number' => $fax_number,
                'email' => $email,
                'url' => $url,
            ]
        );
    }

    public function getCompetitions($association_fpb_id, $season_fpb_id)
    {
        return Association::where('fpb_id', $association_fpb_id)->first()
            ->competitions()->where('season_id', Season::where('fpb_id', $season_fpb_id)->first()->id)
            ->get();
    }
    public function getCompetitionsFromFPB($association_fpb_id, $season_fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109030;++K_ID('.
            $association_fpb_id.')+K_ID_EPOCA('.
            $season_fpb_id.')+CO(PROVAS)+BL(PROVAS)+MYBASEDIV(dAssProvas);+RCNT(100)+RINI(1)&');

        $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=com&id=")]')
            ->each(
                function ($node) use ($association_fpb_id) {
                    CompetitionController::updateOrCreateFromFPB(
                        $association_fpb_id,
                        $node->evaluate('substring-after(@href, "&id=")')[0]
                    );
                }
            );

        return Association::where('fpb_id', $association_fpb_id)->first()
            ->competitions()->where('season_id', Season::where('fpb_id', $season_fpb_id)->first()->id)
            ->get();
    }

    public function getClubs($association_fpb_id)
    {
        return Association::where('fpb_id', $association_fpb_id)->first()
            ->clubs()
            ->get();
    }
    public function getClubsFromFPB($association_fpb_id)
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109012;++K_ID('
            .$association_fpb_id.')+CO(CLUBES)+BL(CLUBES)+MYBASEDIV(dAssoc_Home_Clubes);+RCNT(1000)+RINI(1)&');

        $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]')
            ->each(
                function ($node) {
                    ClubController::updateOrCreateFromFPB(
                        $node->evaluate('substring-after(@href, "&id=")')[0]
                    );
                }
            );

        return Association::where('fpb_id', $association_fpb_id)->first()
            ->clubs()
            ->get();
    }
}
