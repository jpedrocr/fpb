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
    public function getPhases($competition_fpb_id)
    {
        return Competition::where('fpb_id', $competition_fpb_id)
            ->with('competitions', 'phases')
            ->first();
    }
    public static function getPhasesFromFPB($competition_fpb_id, $phases_descriptions = null)
    {
        Competition::getPhasesFromFPB($competition_fpb_id, $phases_descriptions);
        return Competition::where('fpb_id', $competition_fpb_id)->first()
            ->phases()
            ->get();
    }
}
