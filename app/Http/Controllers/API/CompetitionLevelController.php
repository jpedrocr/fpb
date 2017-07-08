<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompetitionLevel;

class CompetitionLevelController extends Controller
{
    public function index()
    {
        return CompetitionLevel::all();
    }
}
