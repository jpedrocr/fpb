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
    public function getCompetitions(Association $association, Season $season)
    {
        return $association->load(['competitions' => function ($query) use ($season) {
            $query->where('season_id', $season->id);
        }]);
    }
    public function getCompetitionsFromFPB(Association $association, Season $season)
    {
        $association->getCompetitionsFromFPB($season);
        return $association->load(['competitions' => function ($query) use ($season) {
            $query->where('season_id', $season->id);
        }]);
    }

    public function getClubs(Association $association)
    {
        return $association->load('clubs');
    }
    public function getClubsFromFPB(Association $association, Request $request)
    {
        $association->getClubsFromFPB($request->club_fpb_id);
        return $association->load('clubs');
    }
}
