<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\CrawlFPBTrait;

class Season extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'seasons';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['fpb_id', 'start_year', 'end_year', 'current'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = ['description'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function competitions()
    {
        return $this->hasMany('App\Model\Competition');
    }
    public function teams()
    {
        return $this->hasMany('App\Model\Team');
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
    public function getDescriptionAttribute()
    {
        return $this->start_year . '/' . $this->end_year;
    }

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
     * Update or Create Season from url
     *
     * @return App\Models\Season
     */
    public static function updateOrCreateFromFPB($fpb_id, $description, $current, $update = true)
    {
        $season = self::where('fpb_id', $fpb_id);
        if (self::newOrUpdate($season, $update)) {
            $years = explode('/', $description);
            return self::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'start_year' =>
                        $years[0],
                    'end_year' =>
                        $years[1],
                    'current' =>
                        $current,
                ]
            );
        } else {
            return $season->first();
        }
    }
    /**
     * Crawl Seasons url
     *
     * @return App\Models\Season
     */
    public static function getSeasonsFromFPB()
    {
        return self::crawlFPB(
            self::seasonsURL(),
            function ($crawler) {
                return self::filter($crawler);
            },
            function ($crawler) {
                return self::eachAny($crawler);
            }
        );
    }
    /**
     * List of Seasons url
     *
     * @return string
     */
    public static function seasonsURL()
    {
        return 'http://www.fpb.pt/fpb2014/do?com=DS;1;.60100;++BL(B1)+CO(B1)+K_ID(10004)'.
            '+MYBASEDIV(dShowCompeticoes);+RCNT(10)+RINI(1)&';
    }
    /**
     * Seasons crawler filter
     *
     * @return Symfony\Component\DomCrawler\Crawler
     */
    public static function filter($crawler)
    {
        return $crawler
            ->filter('option')
            ->reduce(
                function ($node) {
                    return !($node->text() == "(Época)");
                }
            );
    }
    /**
     * Seasons crawler action: Update or Create Season from url
     *
     * @return App\Models\Season
     */
    public static function eachAny($crawler)
    {
        return self::updateOrCreateFromFPB(
            $crawler->attr('value'),
            $crawler->text(),
            $crawler->attr('selected')!=null
        );
    }
}
