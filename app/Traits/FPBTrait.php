<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

trait FPBTrait
{
    public static function crawler($url, $html = null)
    {
        if ($html == null) {
            $client = new Client();
            Log::getMonolog()->info('Goutte::GET::'.$url);
            sleep(1);
            return $client->request('GET', $url);
        } else {
            $crawler = new Crawler();
            $crawler->addHtmlContent($html);
            return $crawler;
        }
    }
    public static function crawlFPB($url, $filterFunction, $crawlerFunction, $html = null)
    {
        if (is_callable($crawlerFunction)) {
            if (is_callable($filterFunction)) {
                $filterFunction(self::crawler($url, $html))
                    ->each($crawlerFunction);
            } else {
                self::crawler($url, $html)
                    ->each($crawlerFunction);
            }
        }
        return self::class;
    }
}
