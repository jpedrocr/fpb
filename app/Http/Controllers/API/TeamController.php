<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        return Team::all();
    }
    public function getCompetitionsAndPhases(Team $team)
    {
        return $team->load('competitions', 'phases');
    }
    public function getCompetitionsAndPhasesFromFPB(Team $team)
    {
        $team->getCompetitionsAndPhasesFromFPB();
        return $team->load('competitions', 'phases');
    }
}
