<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Gender;

class GenderController extends Controller
{
    public function index()
    {
        return Gender::all();
    }
}
