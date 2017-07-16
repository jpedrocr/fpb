<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\CrawlFPBTrait;

use App\Models\Game;

class Round extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'rounds';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = [ 'id' ];
    protected $fillable = [ 'phase_id', 'fpb_id', 'lap_number', 'round_number' ];
    protected $hidden = [ 'created_at', 'updated_at' ];
    protected $dates = [ 'created_at', 'updated_at' ];
    protected $appends = [ 'description' ];

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
            $this->phase->competition->name.' - '.
            $this->phase->description.' - '.
            $this->lap_number.'ª Volta - '.
            $this->round_number.'ª Jornada';
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
                $round->getGamesFromFPB($club_fpb_id);
            }
            return $round;
        } else {
            return $round->first();
        }
    }
    public function getGamesFromFPB($club_fpb_id = null)
    {
        $round = $this;
        if ($club_fpb_id!=null) {
            return $this->crawlFPB(
                'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_JORNADA('.
                    $this->fpb_id.')+CO(JOGOS)+BL(JOGOS)+MYBASEDIV(dJornada_'.
                    $this->fpb_id.');+RCNT(10000)+RINI(1)&',
                function($crawler) {
                    return $crawler->filterXPath('//div[contains(@class, "Tabela01")]/table/tr');
                },
                function($crawler) use ($round, $club_fpb_id) {
                    $tds = $crawler->filterXPath('//td');

                    if ($tds->eq(0)->text()!="Jogo") {
                        $teams = $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
                            ->evaluate('substring-after(@href, "&id=")');
                        $hometeam_fpb_id = $teams[ 0 ];
                        $outteam_fpb_id = $teams[ 1 ];

                        $hometeam = Team::updateOrCreateFromFPB($hometeam_fpb_id, false);
                        $outteam = Team::updateOrCreateFromFPB($outteam_fpb_id, false);

                        if (($hometeam->club()->first()->fpb_id==$club_fpb_id) or
                            ($outteam->club()->first()->fpb_id==$club_fpb_id)) {
                            Game::updateOrCreateFromFPB(
                                $round->fpb_id,
                                $tds->eq(0)->filterXPath('//a[contains(@href, "!site.go?s=1&show=jog&id=")]')
                                    ->evaluate('substring-after(@href, "!site.go?s=1&show=jog&id=")')[ 0 ],
                                $hometeam->id,
                                $outteam->id,
                                trim($tds->eq(11)->text())
                            );
                        }
                    }
                }
            );
        } else {
            return $this->crawlFPB(
                'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_JORNADA('.
                    $round_fpb_id.')+CO(JOGOS)+BL(JOGOS)+MYBASEDIV(dJornada_'.
                    $round_fpb_id.');+RCNT(10000)+RINI(1)&',
                function($crawler) {
                    return $crawler->filterXPath('//div[contains(@class, "Tabela01")]/table/tr');
                },
                function($crawler) use ($round) {
                    $tds = $crawler->filterXPath('//td');

                    if ($tds->eq(0)->text()!="Jogo") {
                        $teams = $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=equ&id=")]')
                            ->evaluate('substring-after(@href, "&id=")');
                        $hometeam_fpb_id = $teams[ 0 ];
                        $outteam_fpb_id = $teams[ 1 ];

                        Game::updateOrCreateFromFPB(
                            $round->fpb_id,
                            $tds->eq(0)->filterXPath('//a[contains(@href, "!site.go?s=1&show=jog&id=")]')
                                ->evaluate('substring-after(@href, "!site.go?s=1&show=jog&id=")')[ 0 ],
                            Team::updateOrCreateFromFPB($hometeam_fpb_id, false)->id,
                            Team::updateOrCreateFromFPB($outteam_fpb_id, false)->id,
                            trim($tds->eq(11)->text())
                        );
                    }
                }
            );
        }
    }
}
