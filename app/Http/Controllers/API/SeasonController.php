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
        Season::getFromFPB();
        return Season::all();
    }
}
