<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Team;
use App\Models\Club;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Agegroup;
use App\Models\Competitionlevel;
use App\Models\Season;
use App\Models\Competition;
use App\Models\Phase;

use App\Http\Controllers\API\CompetitionController;
use App\Http\Controllers\API\ClubController;

class TeamController extends Controller
{
    public function index()
    {
        return Team::all();
    }
    public function getCompetitionsAndPhasesFromFPB($team_fpb_id)
    {
        Team::getCompetitionsAndPhasesFromFPB($team_fpb_id);
        return Team::where('fpb_id', $team_fpb_id)
            ->with('competitions', 'phases')
            ->first();
    }
    public function getSeasonTeams($club_fpb_id, $season_fpb_id)
    {
        return Team::where([
                [ 'club_id', '=', Club::where('fpb_id', $club_fpb_id)->first()->id ],
                [ 'season_id', '=', Season::where('fpb_id', $season_fpb_id)->first()->id ],
            ])
            ->get();
    }
}
