<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AgeGroup;

class AgeGroupController extends Controller
{
    public function index()
    {
        return AgeGroup::all();
    }
}
