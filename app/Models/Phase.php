<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\FPBTrait;

use App\Models\Competition;

class Phase extends Model
{
    use CrudTrait;
    use FPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'phases';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['competition_id', 'fpb_id', 'description', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function competition()
    {
        return $this->belongsTo('App\Models\Competition');
    }
    public function rounds()
    {
        return $this->hasMany('App\Models\Round');
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
    public static function updateOrCreateFromFPB($competition_fpb_id, $fpb_id, $description, $status, $update = true)
    {
        $phase = Phase::where('fpb_id', $fpb_id);
        if (($phase->count()==0) or ($update)) {
            return Phase::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'competition_id' =>
                        Competition::where('fpb_id', $competition_fpb_id)->first()->id,
                    'description' =>
                        $description,
                    'status' =>
                        $status,
                ]
            );
        } else {
            return $phase->first();
        }
    }
    public static function getRoundsFromFPB($phase_fpb_id, $club_fpb_id = null)
    {
        $crawler = self::crawler('http://www.fpb.pt/fpb2014/do?com=DS;1;.100014;++K_ID_COMPETICAO_FASE('
            .$phase_fpb_id.')+CO(JORNADAS)+BL(JORNADAS)+MYBASEDIV(dFase_'.$phase_fpb_id.');+RCNT(100000)+RINI(1)&');

        $fpb_ids = $crawler->filterXPath('//div[contains(@id, "dJornada_")]');
        $descriptions = $crawler->filterXPath('//div[contains(@class, "Titulo03")]');

        if ($fpb_ids->count() == $descriptions->count()) {
            for ($i=0; $i < $fpb_ids->count(); $i++) {
                $description = explode(' ª volta', $descriptions->eq($i)->text());

                Round::updateOrCreateFromFPB(
                    $phase_fpb_id,
                    $fpb_ids->eq($i)->evaluate('substring-after(@id, "dJornada_")')[0],
                    trim($description[0]),
                    substr(trim(explode('ª jornada', $description[1])[0]), 8),
                    $club_fpb_id
                );
            }
        }
        return self::class;
    }
}
