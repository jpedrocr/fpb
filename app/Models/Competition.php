<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Competition extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'competitions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['association_id', 'category_id', 'fpb_id', 'name', 'image', 'age_group_id', 'competition_level_id', 'season_id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
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
    public function association()
    {
        return $this->belongsTo('App\Models\Association');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function age_group()
    {
        return $this->belongsTo('App\Models\AgeGroup');
    }
    public function competition_level()
    {
        return $this->belongsTo('App\Models\CompetitionLevel');
    }
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
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
}
