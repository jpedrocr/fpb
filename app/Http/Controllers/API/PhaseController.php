<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Phase;

class PhaseController extends Controller
{
    public function index()
    {
        return Phase::all();
    }
    public function getRounds(Phase $phase)
    {
        return $phase->load('rounds', 'rounds.games');
    }
    public function getRoundsFromFPB(Phase $phase, Request $request)
    {
        $phase->getRoundsFromFPB($request->club_fpb_id);
        return $phase->load('rounds', 'rounds.games', 'rounds.games.hometeam', 'rounds.games.outteam');
    }
}
