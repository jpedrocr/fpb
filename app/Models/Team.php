<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\CrawlFPBTrait;

use App\Models\Club;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;
use App\Models\Competition;
use App\Models\Phase;
use App\Models\Game;

class Team extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'teams';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'club_id', 'category_id', 'fpb_id', 'name', 'image', 'agegroup_id', 'competitionlevel_id', 'season_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function agegroup()
    {
        return $this->belongsTo('App\Models\Agegroup');
    }
    public function competitionlevel()
    {
        return $this->belongsTo('App\Models\Competitionlevel');
    }
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }
    public function competitions()
    {
        return $this->belongsToMany('App\Models\Competition');
    }
    public function phases()
    {
        return $this->belongsToMany('App\Models\Phase');
    }
    public function homegames()
    {
        return $this->hasMany('App\Model\Game', 'hometeam_id');
    }
    public function outgames()
    {
        return $this->hasMany('App\Model\Game', 'outteam_id');
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
        $team = Team::where('fpb_id', $fpb_id);
        if (($team->count()==0) || ($update)) {
            $crawler = self::crawler('http://www.fpb.pt/fpb2014/!site.go?s=1&show=equ&id='.$fpb_id);

            $node = $crawler->filterXPath('//div[@class="Equipa_Header"]');
            $club_details = $node->filterXPath('//div/span[@class="Info"]');

            $club_fpb_id = $node->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]')
                ->evaluate('substring-after(@href, "&id=")')[0];

            return Team::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'club_id' =>
                        Club::updateOrCreateFromFPB($club_fpb_id, false)->id,
                    'category_id' =>
                        Category::where('fpb_id', 'equ')->first()->id,
                    'name' =>
                        trim($node->filterXPath('//div[@id="NomeClube"]')->text()),
                    'image' =>
                        $node->filterXPath('//div[@id="Logo"]/img')->attr('src'),
                    'season_id' =>
                        Season::where('current', true)->first()->id,
                    'agegroup_id' =>
                        Agegroup::firstOrCreate(
                            ['description' => $club_details->eq(0)->text()],
                            ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                        )->id,
                    'competitionlevel_id' =>
                        Competitionlevel::firstOrCreate(
                            ['description' => $club_details->eq(1)->text()],
                            ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                        )->id,
                ]
            );
        } else {
            return $team->first();
        }
    }
    public function getCompetitionsAndPhasesFromFPB()
    {
        $team = $this;
        return $this->crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;317.104000;++ID('
            .$this->fpb_id
            .')+CO(COMPETICOES)+BL(COMPETICOES);+MYBASEDIV(dEquipa_Ficha_Home_Comp);+RCNT(1000)+RINI(1)&',
            function ($crawler) {
                return $crawler->filterXPath('//div[contains(@class, "LinhaSeparadora01")]');
            },
            function ($crawler) use ($team) {
                $competition_fpb_id = $crawler
                    ->nextAll()
                    ->eq(0)
                    ->filterXPath('//a[contains(@href, "!site.go?s=1&show=com&id=")]')
                    ->evaluate('substring-after(@href, "&id=")')[0];

                $competition = Competition::where('fpb_id', $competition_fpb_id)->first();

                if ($team->competitions()->where('id', $competition->id)->count()==0) {
                    $team->competitions()->attach($competition->id);
                }

                $nextAll = $crawler->nextAll();
                $eq = 1;
                while (($eq<$nextAll->count()) && ($nextAll->eq($eq)->attr('class')=="Titulo04 TextoCor01")) {
                    $phase_description = trim(explode("\n", $nextAll->eq($eq)->text())[2]);

                    if ($competition->phases()->where('description', $phase_description)->count()==0) {
                        $competition->getPhasesFromFPB([$phase_description]);
                    }

                    if ($team->phases()->where('description', $phase_description)->count()==0) {
                        $phase_id = Phase::where([
                                [ 'competition_id', '=', $competition->id ],
                                [ 'description', '=', $phase_description ],
                            ])->first()->id;
                        $team->phases()->attach($phase_id);
                    }
                    $eq++;
                }
            }
        );
    }
}
