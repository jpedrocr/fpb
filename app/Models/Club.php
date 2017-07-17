<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\CrawlFPBTrait;

use App\Models\Association;
use App\Models\Category;
use App\Models\Season;
use App\Models\Team;

class Club extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'clubs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'association_id', 'category_id', 'fpb_id', 'name', 'image', 'alternative_name', 'founding_date', 'president',
        'address', 'telephone', 'fax_number', 'email', 'url'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function association()
    {
        return $this->belongsTo('App\Models\Association');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'fpb_id';
    }
    public static function updateOrCreateFromFPB($fpb_id, $update = true)
    {
        $club = Club::where('fpb_id', $fpb_id);
        if (($club->count()==0) || ($update)) {
            $crawler = self::crawler('http://www.fpb.pt/fpb2014/!site.go?s=1&show=clu&id='.$fpb_id);

            $club_details = $crawler->filterXPath('//table[@class="TabelaHor01"]/tr/td');

            $original_address = explode("<br>", trim($club_details->eq(2)->html()));
            $address1 = trim($original_address[0]);
            $address2 = trim($original_address[1]);

            $association_fpb_id = $club_details->eq(3)->filterXPath('//a')
                ->evaluate('substring-after(@href, "&id=")')[0];

            return Club::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'association_id' =>
                        Association::updateOrCreateFromFPB($association_fpb_id, false)->id,
                    'category_id' =>
                        Category::where('fpb_id', 'clu')->first()->id,
                    'name' =>
                        $crawler->filterXPath('//div/div[@id="NomeClube"]')->text(),
                    // 'alternative_name' =>
                    //     $alternative_name,
                    'image' =>
                        $crawler->filterXPath('//div/div[@id="Logo"]/img')->attr('src'),
                    'founding_date' =>
                        trim($club_details->eq(0)->text()),
                    'president' =>
                        trim($club_details->eq(1)->text()),
                    'address' =>
                        implode("\n", $original_address),
                    'telephone' =>
                        trim($club_details->eq(5)->text()),
                    'fax_number' =>
                        trim($club_details->eq(6)->text()),
                    'email' =>
                        trim($club_details->eq(7)->text()),
                    'url' =>
                        trim($club_details->eq(8)->text()),
                    // 'venue_id' =>
                    //     Venue::where('name', trim($club_details->eq(4)->text()))->first()->id,
                ]
            );
        } else {
            return $club->first();
        }
    }
    /**
     * Clubs crawler filter
     *
     * @return Symfony\Component\DomCrawler\Crawler
     */
    public static function filter($crawler)
    {
        return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]');
    }
    /**
     * Clubs crawler action: Update or Create Club from url, if it's the requested Club
     *
     * @return App\Models\Club
     */
    public static function eachOne($crawler, $club_fpb_id)
    {
        $fpb_id = $crawler->evaluate('substring-after(@href, "&id=")')[0];
        if ($club_fpb_id == $fpb_id) {
            Club::updateOrCreateFromFPB(
                $fpb_id
            );
        }
    }
    /**
     * Clubs crawler action: Update or Create Club from url
     *
     * @return App\Models\Club
     */
    public static function eachAny($crawler)
    {
        Club::updateOrCreateFromFPB(
            $crawler->evaluate('substring-after(@href, "&id=")')[0]
        );
    }

    public function getTeamsFromFPB()
    {
        return $this->crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;.105010;++K_ID_CLUBE('
                .$this->fpb_id
                .')+CO(EQUIPAS)+BL(EQUIPAS-02);+MYBASEDIV(dClube_Ficha_Home_Equipas);+RCNT(1000)+RINI(1)&',
            function ($crawler) {
                return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]');
            },
            function ($crawler) {
                Team::updateOrCreateFromFPB(
                    $crawler->evaluate('substring-after(@href, "&id=")')[0]
                );
            }
        );
    }
}
