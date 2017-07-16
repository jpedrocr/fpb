<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Competition;

class CompetitionController extends Controller
{
    public function index()
    {
        return Competition::all();
    }
    public function getPhases(Competition $competition)
    {
        return $competition->load('competitions', 'phases');
    }
    public static function getPhasesFromFPB(Competition $competition, Request $request)
    {
        $competition->getPhasesFromFPB($request->phases_descriptions);
        return $competition->load('competitions', 'phases');
    }
}
