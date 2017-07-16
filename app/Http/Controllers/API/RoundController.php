<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Round;

class RoundController extends Controller
{
    public function index()
    {
        return Round::all();
    }
    public static function getGames(Round $round)
    {
        return $round->load('games');
    }
    public static function getGamesFromFPB(Round $round)
    {
        $round->getGamesFromFPB($request->club_fpb_id);
        return $round->load('games');
    }
}
