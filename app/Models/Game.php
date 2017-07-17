<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\CrawlFPBTrait;
use Carbon\Carbon;

use App\Models\Round;
use App\Models\Category;
use App\Models\Team;

class Game extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'games';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'round_id', 'category_id', 'fpb_id', 'hometeam_id', 'outteam_id',
        'number', 'schedule', 'home_result', 'out_result', 'status'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['schedule', 'created_at', 'updated_at'];
    protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function round()
    {
        return $this->belongsTo('App\Models\Round');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function hometeam()
    {
        return $this->belongsTo('App\Models\Team', 'hometeam_id');
    }
    public function outteam()
    {
        return $this->belongsTo('App\Models\Team', 'outteam_id');
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
    public static function updateOrCreateFromFPB(
        $round_fpb_id,
        $fpb_id,
        $hometeam_id,
        $outteam_id,
        $status,
        $update = true
    ) {
        $game = Game::where('fpb_id', $fpb_id);
        if (($game->count() == 0) || ($update)) {
            $crawler = self::crawler('http://www.fpb.pt/fpb2014/!site.go?s=1&show=jog&id=' . $fpb_id);

            $game_details = $crawler->filterXPath('//table[@class="JOG_Infox"]/tr/td');
            $date = explode("/", $game_details->eq(2)->text());
            $time = explode(":", str_replace('.', ':', $game_details->eq(3)->text()));

            if (is_array($date) && is_array($time) && (count($date) == 3) && (count($time) == 2)) {
                $schedule = Carbon::create(
                    $date[2],
                    $date[1],
                    $date[0],
                    $time[0],
                    $time[1],
                    0,
                    'Europe/Lisbon'
                );
            } else {
                $schedule = null;
            }

            $results = $crawler->filterXPath('//div[@class="Centro"]//table//table/tr/td[@class="GameScoreFont01"]');

            return Game::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'round_id' =>
                        Round::where('fpb_id', $round_fpb_id)->first()->id,
                    'category_id' =>
                        Category::where('fpb_id', 'jog')->first()->id,
                    'hometeam_id' =>
                        $hometeam_id,
                    'outteam_id' =>
                        $outteam_id,
                    'number' =>
                        $game_details->eq(0)->text(),
                    'schedule' =>
                        $schedule,
                    'home_result' =>
                        $results->eq(0)->text() != '' ? $results->eq(0)->text() : null,
                    'out_result' =>
                        $results->eq(1)->text() != '' ? $results->eq(1)->text() : null,
                    'status' =>
                        $status,
                ]
            );
        } else {
            return $game->first();
        }
    }
}
