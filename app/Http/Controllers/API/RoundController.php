<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoundController extends Controller
{
    public function index()
    {
        return Round::all();
    }
    public static function getGamesFromFPB($round_fpb_id, $club_fpb_id = null)
    {
        Round::getGamesFromFPB($round_fpb_id, $club_fpb_id);
        return Round::where('fpb_id', $round_fpb_id)->first()
            ->games()
            ->get();
    }
}
