<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Association;
use App\Models\Category;

class AssociationController extends Controller
{
    public function index()
    {
        return Association::all();
    }
    public function getFromFPB()
    {
        // $html = '';
        // $crawler = new Crawler();
        // $crawler->addHtmlContent($html);

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109050;++BL(B1)+CO(B1)+MYBASEDIV(dShowAssociacoes);+RCNT(10)+RINI(1)&');

        $crawler
            ->filterXPath('//div[@id="Associacao"]')
            ->each(function ($node) {
                $fpb_id = $node->filterXPath('//div[@id="Descricao"]/a')->evaluate('substring-after(@href, "&id=")')[0];
                $name = $node->filterXPath('//div[@id="Descricao"]/a')->text();
                $image = $node->filterXPath('//div[@id="Logo"]/img')->attr('src');
                if (Association::where('fpb_id',$fpb_id)->count()==0) {
                    Association::create([
                        'category_id' => Category::where('fpb_id','ass')->first()->id,
                        'fpb_id' => $fpb_id,
                        'name' => $name,
                        'image' => $image,
                    ]);
                //     dump('Association '.$fpb_id.'->'.$name.'->'.$image.' created');
                // }
                // else {
                //     dump('Association '.$fpb_id.'->'.$name.'->'.$image.' exists');
                }
            });
        return Association::all();
    }
}
