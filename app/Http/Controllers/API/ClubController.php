<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Club;
use App\Models\Season;

class ClubController extends Controller
{
    public function index()
    {
        return Club::all();
    }
    public function getTeams(Club $club)
    {
        return $club->load(['teams' => function ($query) {
            $query->where('season_id', Season::where('current', true)->first()->id);
        }]);
    }
    public function getTeamsFromFPB(Club $club)
    {
        $club->getTeamsFromFPB();
        return $club->load(['teams' => function ($query) {
            $query->where('season_id', Season::where('current', true)->first()->id);
        }]);
    }
}
