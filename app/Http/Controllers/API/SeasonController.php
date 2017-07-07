<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Season;

class SeasonController extends Controller
{
    public function index()
    {
        return Season::all();
    }
    public function getFromFPB()
    {
        return 'aaa';
        // return Season::::create([
        //     'fpb_id' => 55,
        //     'start_year' => 2016,
        //     'end_year' => 2017,
        //     'current' => true,
        // ]);
    }
}
