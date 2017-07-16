<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Association;
use App\Models\Season;

class AssociationController extends Controller
{
    public function index()
    {
        return Association::all();
    }
    public function getFromFPB()
    {
        Association::getAssociationsFromFPB();
        return Association::all();
    }
    public function getCompetitions($association_fpb_id, $season_fpb_id)
    {
        return Association::where('fpb_id', $association_fpb_id)
            ->with(['competitions' => function ($query) use ($season_fpb_id) {
                $query->where('season_id', Season::where('fpb_id', $season_fpb_id)->first()->id);
            }])->first();
    }
    public function getCompetitionsFromFPB($association_fpb_id, $season_fpb_id)
    {
        Association::getCompetitionsFromFPB($association_fpb_id, $season_fpb_id);
        return Association::where('fpb_id', $association_fpb_id)
            ->with(['competitions' => function ($query) use ($season_fpb_id) {
                $query->where('season_id', Season::where('fpb_id', $season_fpb_id)->first()->id);
            }])->first();
    }

    public function getClubs($association_fpb_id)
    {
        return Association::where('fpb_id', $association_fpb_id)
            ->with('clubs')
            ->first();
    }
    public function getClubsFromFPB($association_fpb_id)
    {
        Association::getClubsFromFPB($association_fpb_id, $season_fpb_id);
        return Association::where('fpb_id', $association_fpb_id)
            ->with('clubs')
            ->first();
    }
}
