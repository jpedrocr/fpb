<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\FPBTrait;
use Carbon\Carbon;

use App\Models\Round;
use App\Models\Category;
use App\Models\Team;

class Game extends Model
{
    use CrudTrait;
    use FPBTrait;

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
    public static function updateOrCreateFromFPB($round_fpb_id, $fpb_id, $status, $club_fpb_id = null, $update = true)
    {
        $game = Game::where('fpb_id', $fpb_id);
        if (($game->count()==0) or ($update)) {
            $crawler = self::crawler('http://www.fpb.pt/fpb2014/!site.go?s=1&show=jog&id='.$fpb_id);

            $teams = $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
                ->evaluate('substring-after(@href, "&id=")');
            $hometeam_fpb_id = $teams[0];
            $outteam_fpb_id = $teams[1];

            $game_details = $crawler->filterXPath('//table[@class="JOG_Infox"]/tr/td');
            $date = explode("/", $game_details->eq(2)->text());
            $time = explode(":", str_replace('.', ':', $game_details->eq(3)->text()));

            if (is_array($date) and is_array($time) and (count($date) == 3) and (count($time) == 2)) {
                $schedule = Carbon::create($date[2], $date[1], $date[0], $time[0], $time[1], 0, 'Europe/Lisbon');
            } else {
                $schedule = null;
            }

            $results = $crawler->filterXPath('//div[@class="Centro"]//table//table/tr/td[@class="GameScoreFont01"]');

            if ($club_fpb_id!=null) {
                $hometeam = Team::updateOrCreateFromFPB($hometeam_fpb_id, false);
                $outteam = Team::updateOrCreateFromFPB($outteam_fpb_id, false);
                if (($hometeam->club()->first()->fpb_id == $club_fpb_id) or
                    ($outteam->club()->first()->fpb_id == $club_fpb_id) ) {
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
                                $hometeam->id,
                            'outteam_id' =>
                                $outteam->id,
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
                }
            } else {
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
                            Team::updateOrCreateFromFPB($hometeam_fpb_id, false)->id,
                        'outteam_id' =>
                            Team::updateOrCreateFromFPB($outteam_fpb_id, false)->id,
                        'number' =>
                            $game_details->eq(0)->text(),
                        'schedule' =>
                            Carbon::create($date[2], $date[1], $date[0], $time[0], $time[1], 0, 'Europe/Lisbon'),
                        'home_result' =>
                            $results->eq(0)->text(),
                        'out_result' =>
                            $results->eq(1)->text(),
                        'status' =>
                            $status,
                    ]
                );
            }
        } else {
            return $game->first();
        }
    }
}
