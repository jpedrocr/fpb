<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\FPBTrait;

use App\Models\Competition;
use App\Models\Association;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;

class Competition extends Model
{
    use CrudTrait;
    use FPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'competitions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'association_id', 'category_id', 'fpb_id', 'name', 'image', 'agegroup_id', 'competitionlevel_id', 'season_id'
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
    public function phases()
    {
        return $this->hasMany('App\Models\Phase');
    }
    public function teams()
    {
        return $this->belongsToMany('App\Models\Team');
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
    public static function updateOrCreateFromFPB($association_fpb_id, $fpb_id, $update = true)
    {
        $competition = Club::where('fpb_id', $fpb_id);
        if (($competition->count()==0) or ($update)) {
            $crawler = self::crawler('http://www.fpb.pt/fpb2014/!site.go?s=1&show=com&id='.$fpb_id);

            $node = $crawler->filterXPath('//div[@class="COM_Header"]');

            $competition_details = $node->filterXPath('//div/div[@id="OutrosDados"]/strong');
            $description = explode("/", $competition_details->eq(2)->text());
            $start_year = $description[0];
            $end_year = $description[1];

            return Competition::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'association_id' =>
                        Association::updateOrCreateFromFPB($association_fpb_id, false)->id,
                    'category_id' =>
                        Category::firstOrCreate(['fpb_id' => 'com'])->id,
                    'name' =>
                        trim($node->filterXPath('//div/div[@id="Nome"]')->text()),
                    'image' =>
                        $node->filterXPath('//div/div[@id="Logo"]/img')->attr('src'),
                    'agegroup_id' =>
                        Agegroup::firstOrCreate(
                            ['description' => $competition_details->eq(0)->text()],
                            ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                        )->id,
                    'competitionlevel_id' =>
                        Competitionlevel::firstOrCreate(
                            ['description' => $competition_details->eq(1)->text()],
                            ['gender_id' => Gender::where('fpb_id', '-')->first()->id]
                        )->id,
                    'season_id' =>
                        Season::where([
                            ['start_year', '=', $start_year],
                            ['end_year', '=', $end_year],
                        ])->first()->id,
                ]
            );
        } else {
            return $competition->first();
        }
    }
    public static function getPhasesFromFPB($competition_fpb_id, $phases_descriptions = null)
    {
        return self::crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO('.
                $competition_fpb_id.')+CO(FASES)+BL(FASES)+MYBASEDIV(dCompFases);+RCNT(10000)+RINI(1)&',
            function ($crawler) {
                return $crawler->filterXPath('//div[contains(@style, "margin:10px;")]');
            },
            function ($crawler) use ($competition_fpb_id, $phases_descriptions) {
                $fpb_id = $crawler->filterXPath('//div[contains(@id, "dFase_")]')
                    ->evaluate('substring-after(@id, "dFase_")')[0];
                $description = trim($crawler->filterXPath('//div[contains(@class, "Titulo01")]')->text());
                if ($phases_descriptions != null) {
                    if (in_array($description, $phases_descriptions)) {
                        Phase::updateOrCreateFromFPB(
                            $competition_fpb_id,
                            $fpb_id,
                            $description,
                            explode("\n", $crawler->text())[3]
                        );
                    }
                } else {
                    Phase::updateOrCreateFromFPB(
                        $competition_fpb_id,
                        $fpb_id,
                        $description,
                        explode("\n", $crawler->text())[3]
                    );
                }
            }
        );
    }
}
