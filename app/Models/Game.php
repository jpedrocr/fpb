<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Game extends Model
{
    use CrudTrait;

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
}
