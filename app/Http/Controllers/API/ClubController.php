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
    public function getTeams($club_fpb_id)
    {
        return Club::where('fpb_id', $club_fpb_id)->with(['teams' => function ($query) {
            $query->where('season_id', Season::where('current', true)->first()->id);
        }])->first();
    }
    public function getTeamsFromFPB($club_fpb_id)
    {
        Club::getTeamsFromFPB($club_fpb_id);
        return Club::where('fpb_id', $club_fpb_id)->with(['teams' => function ($query) {
            $query->where('season_id', Season::where('current', true)->first()->id);
        }])->first();
    }
    public function getSeasonTeams($club_fpb_id, $season_fpb_id)
    {
        return Club::where('fpb_id', $club_fpb_id)->with(['teams' => function ($query) use ($season_fpb_id) {
            $query->where('season_id', Season::where('fpb_id', $season_fpb_id)->first()->id);
        }])->first();
    }
}
