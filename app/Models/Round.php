<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\FPBTrait;

use App\Models\Game;

class Round extends Model
{
    use CrudTrait;
    use FPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'rounds';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['phase_id', 'fpb_id', 'lap_number', 'round_number'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = ['description'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function phase()
    {
        return $this->belongsTo('App\Models\Phase');
    }
    public function games()
    {
        return $this->hasMany('App\Models\Game');
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
        return
            $this->phase->competition->name . ' - ' .
            $this->phase->description . ' - ' .
            $this->lap_number . 'ª Volta - ' .
            $this->round_number . 'ª Jornada';
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
    public static function updateOrCreateFromFPB(
        $phase_fpb_id,
        $fpb_id,
        $lap_number,
        $round_number,
        $club_fpb_id = null,
        $update = true
    ) {
        $round = Round::where('fpb_id', $fpb_id);
        if (($round->count()==0) or ($update)) {
            $round = Round::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'phase_id' =>
                        Phase::where('fpb_id', $phase_fpb_id)->first()->id,
                    'lap_number' =>
                        $lap_number,
                    'round_number' =>
                        $round_number,
                ]
            );
            if ($club_fpb_id!=null) {
                Round::getGamesFromFPB($fpb_id, $club_fpb_id);
            }
            return $round;
        } else {
            return $round->first();
        }
    }
    public static function getGamesFromFPB($round_fpb_id, $club_fpb_id = null)
    {
        return self::crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_JORNADA('.
                $round_fpb_id.')+CO(JOGOS)+BL(JOGOS)+MYBASEDIV(dJornada_'.
                $round_fpb_id.');+RCNT(10000)+RINI(1)&',
            function ($crawler) {
                return $crawler->filterXPath('//div[contains(@class, "Tabela01")]/table/tr');
            },
            function ($crawler) use ($round_fpb_id, $club_fpb_id) {
                $tds = $crawler->filterXPath('//td');
                if ($tds->eq(0)->text()!="Jogo") {
                    Game::updateOrCreateFromFPB(
                        $round_fpb_id,
                        $tds->eq(0)->filterXPath('//a[contains(@href, "!site.go?s=1&show=jog&id=")]')
                            ->evaluate('substring-after(@href, "!site.go?s=1&show=jog&id=")')[0],
                        trim($tds->eq(11)->text()),
                        $club_fpb_id
                    );
                }
            }
        );
    }
}
