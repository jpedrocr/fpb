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
    public function getRounds($phase_fpb_id)
    {
        return Phase::where('fpb_id', $phase_fpb_id)
            ->with('rounds')
            ->first();
    }
    public function getRoundsFromFPB($phase_fpb_id, $club_fpb_id = null)
    {
        Phase::getRoundsFromFPB($phase_fpb_id, $club_fpb_id);
        return Phase::where('fpb_id', $phase_fpb_id)
            ->with('rounds')
            ->first();
    }
}
