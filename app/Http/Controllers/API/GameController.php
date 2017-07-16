<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\API\TeamController;

class GameController extends Controller
{
    public function index()
    {
        return Game::all();
    }
}
