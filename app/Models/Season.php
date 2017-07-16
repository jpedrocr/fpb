<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\FPBTrait;

class Season extends Model
{
    use CrudTrait;
    use FPBTrait;

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
    public static function updateOrCreateFromFPB($fpb_id, $description, $current, $update = true)
    {
        $season = Season::where('fpb_id', $fpb_id);
        if (($season->count()==0) or ($update)) {
            $years = explode('/', $description);
            Season::updateOrCreate(
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
    public static function getFromFPB()
    {
        return self::crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;.60100;++BL(B1)+CO(B1)+K_ID(10004)'.
                '+MYBASEDIV(dShowCompeticoes);+RCNT(10)+RINI(1)&',
            function ($crawler) {
                return $crawler
                    ->filter('option')
                    ->reduce(
                        function ($node) {
                            return !($node->text() == "(Época)");
                        }
                    );
            },
            function ($crawler) {
                self::updateOrCreateFromFPB(
                    $crawler->attr('value'),
                    $crawler->text(),
                    $crawler->attr('selected')!=null
                );
            }
        );
    }
}
