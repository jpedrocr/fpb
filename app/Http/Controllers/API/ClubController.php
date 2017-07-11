<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Club;
use App\Models\Association;
use App\Models\Category;

class ClubController extends Controller
{
    public function index()
    {
        return Club::all();
    }
    public function indexFromAssociation($association_fpb_id)
    {
        return Club::where('association_id', '=', Association::where('fpb_id', $association_fpb_id)->first()->id)
            ->get();
    }
    public function getFromFPB($association_fpb_id)
    {
        $association_id = Association::where('fpb_id', $association_fpb_id)->first()->id;

        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109012;++K_ID('
            .$association_fpb_id.')+CO(CLUBES)+BL(CLUBES)+MYBASEDIV(dAssoc_Home_Clubes);+RCNT(1000)+RINI(1)&');

        $crawler
            ->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]')
            ->each(function ($node) use ($association_id) {
                $fpb_id = $node->evaluate('substring-after(@href, "&id=")')[0];
                $alternative_name = trim($node->text());

                if (Club::where('fpb_id', $fpb_id)->count()==0) {

                    // $html2 = '';
                    // $crawler2 = new Crawler();
                    // $crawler2->addHtmlContent($html2);

                    $client2 = new Client();
                    $crawler2 = $client2->request('GET', 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=clu&id='.$fpb_id.
                        '&layout=calendario');

                    $image = $crawler2->filterXPath('//div/div[@id="Logo"]/img')->attr('src');
                    $name = $crawler2->filterXPath('//div/div[@id="NomeClube"]')->text();

                    $club_details = $crawler2->filterXPath('//table[@class="TabelaHor01"]/tr/td');

                    $founding_date = trim($club_details->eq(0)->text());
                    $president = trim($club_details->eq(1)->text());
                    $original_address = explode("<br>",trim($club_details->eq(2)->html()));
                    $address1 = trim($original_address[0]);
                    $address2 = trim($original_address[1]);
                    // dump($club_details->eq(2)->html()."\n".$address1."\n".$address2);
                    $address = implode("\n", $original_address);
                    $venue_name = trim($club_details->eq(4)->text());
                    $telephone = trim($club_details->eq(5)->text());
                    $fax_number = trim($club_details->eq(6)->text());
                    $email = trim($club_details->eq(7)->text());
                    $url = trim($club_details->eq(8)->text());

                    // if (Venue::where('name', $venue_name)->count()==0) {
                    //     $venue_id = Venue::create([
                    //         'fpb_id' => ???,
                    //         'name' => $venue_name,
                    //     ])->id;
                    // } else {
                    //     $venue_id = Venue::where('name', $venue_name)->first()->id;
                    // }

                    Club::create([
                        'association_id' => $association_id,
                        'category_id' => Category::where('fpb_id', 'clu')->first()->id,
                        'fpb_id' => $fpb_id,
                        'name' => $name,
                        'image' => $image,
                        'alternative_name' => $alternative_name,
                        'founding_date' => $founding_date,
                        'president' => $president,
                        'address' => $address,
                        'telephone' => $telephone,
                        'fax_number' => $fax_number,
                        'email' => $email,
                        'url' => $url,
                        // 'venue_id' => $venue_id,
                    ]);
                //     dump("Club ".$fpb_id."->\n".$name."->\n".$image."->\n".$alternative_name."->\n".$founding_date."->\n".$president."->\n".$address."->\n".$telephone."->\n".$fax_number."->\n".$email."->\n".$url." created");
                // } else {
                //     dump("Club ".$fpb_id."->\n".$alternative_name." exists");
                }
            });
        return Club::where('association_id', '=', $association_id)->get();
    }
}
