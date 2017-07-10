<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Competitionlevel;

class CompetitionlevelController extends Controller
{
    public function index()
    {
        return Competitionlevel::all();
    }
}
