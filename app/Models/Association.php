<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\Log;

use App\Traits\CrawlFPBTrait;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Club;

class Association extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'associations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'category_id', 'fpb_id', 'name', 'image', 'president', 'technical_director', 'cad_president', 'address',
        'telephone', 'fax_number', 'email', 'url'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function competitions()
    {
        return $this->hasMany('App\Models\Competition');
    }
    public function clubs()
    {
        return $this->hasMany('App\Models\Club');
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

    /**
     * Update or Create Association from url
     *
     * @return App\Models\Association
     */
    public static function updateOrCreateFromFPB($fpb_id, $update = true)
    {
        $association = Association::where('fpb_id', $fpb_id);
        if (self::newOrUpdate($association, $update)) {
            $crawler = self::crawler(self::urlAssociation($fpb_id));

            $content = $crawler->filterXPath('//div[@id="dConteudosx"]');

            $association_details = $content->filterXPath('//div/table[@class="TabelaHor01"]/tr/td');

            $original_address = explode("<br>", trim($association_details->eq(3)->html()));
            $address1 = trim($original_address[0]);
            $address2 = trim($original_address[1]);

            return Association::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'category_id' =>
                        Category::firstOrCreate(['fpb_id' => 'ass'])->id,
                    'name' =>
                        trim($content->filterXPath('//div/div[@class="Assoc_FichaHeader_Nome"]/div')->text()),
                    'image' =>
                        $content->filterXPath('//div/div[@class="Assoc_FichaHeader_Foto"]/img')->attr('src'),
                    'president' =>
                        trim($association_details->eq(0)->text()),
                    'technical_director' =>
                        trim($association_details->eq(1)->text()),
                    'cad_president' =>
                        trim($association_details->eq(2)->text()),
                    'address' =>
                        implode("\n", $original_address),
                    'telephone' =>
                        trim($association_details->eq(4)->text()),
                    'fax_number' =>
                        trim($association_details->eq(5)->text()),
                    'email' =>
                        trim($association_details->eq(6)->filterXPath('//a')
                            ->evaluate('substring-after(@href, "mailto:")')[0]),
                    'url' =>
                        trim($association_details->eq(7)->filterXPath('//a')->attr('href')),
                ]
            );
        } else {
            return $association->first();
        }
    }
    /**
     * Association is new or will be updated?
     *
     * @return boolean
     */
    public static function newOrUpdate($association, $update)
    {
        return (($association->count() == 0) || ($update));
    }
    /**
     * Single Association url
     *
     * @return string
     */
    public static function associationURL($fpb_id)
    {
        return 'http://www.fpb.pt/fpb2014/!site.go?s=1&show=ass&id=' . $fpb_id;
    }
    /**
     * Crawl Associations url
     *
     * @return App\Models\Association
     */
    public static function getAssociationsFromFPB()
    {
        self::updateOrCreateFromFPB(50);
        return self::crawlFPB(
            self::associationsURL(),
            function ($crawler) {
                return self::filter($crawler);
            },
            function ($crawler) {
                return self::eachAny($crawler);
            }
        );
    }
    /**
     * List of Associations url
     *
     * @return string
     */
    public static function associationsURL()
    {
        return 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109050;++BL(B1)+CO(B1)+' .
            'MYBASEDIV(dShowAssociacoes);+RCNT(10)+RINI(1)&';
    }
    /**
     * Associations crawler filter
     *
     * @return Symfony\Component\DomCrawler\Crawler
     */
    public static function filter($crawler)
    {
        return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=ass&id=")]');
    }
    /**
     * Associations crawler action: Update or Create Association from url
     *
     * @return App\Models\Association
     */
    public static function eachAny($crawler)
    {
        Association::updateOrCreateFromFPB(
            $crawler->evaluate('substring-after(@href, "&id=")')[0]
        );
    }

    /**
     * Crawl Competitions url
     *
     * @return App\Models\Competition
     */
    public function getCompetitionsFromFPB(Season $season)
    {
        return $this->crawlFPB(
            $this->urlCompetitions($season),
            function ($crawler) {
                return Competition::filter($crawler);
            },
            function ($crawler) {
                return Competition::eachAny($crawler, $this->fpb_id);
            }
        );
    }
    /**
     * List of Competitions url
     *
     * @return string
     */
    public function associationCompetitionsURL(Season $season)
    {
        return 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109030;++K_ID(' .
            $this->fpb_id . ')+K_ID_EPOCA(' .
            $season->fpb_id . ')+CO(PROVAS)+BL(PROVAS)+MYBASEDIV(dAssProvas);+RCNT(100)+RINI(1)&';
    }
    /**
     *
     * Crawl Clubs url
     *
     * @return App\Models\Club
     */
    public function getClubsFromFPB($club_fpb_id = null)
    {
        return $this->crawlFPB(
            $this->associationClubsURL(),
            function ($crawler) {
                return Club::filter($crawler);
            },
            $club_fpb_id != null ?
                function ($crawler) {
                    return Club::eachOne($crawler, $club_fpb_id);
                } :
                function ($crawler) {
                    return Club::eachAny($crawler);
                }
        );
    }
    /**
     * List of Clubs url
     *
     * @return string
     */
    public function associationClubsURL()
    {
        return 'http://www.fpb.pt/fpb2014/do?com=DS;1;.109012;++K_ID('.
            $this->fpb_id .
            ')+CO(CLUBES)+BL(CLUBES)+MYBASEDIV(dAssoc_Home_Clubes);+RCNT(1000)+RINI(1)&';
    }
}
