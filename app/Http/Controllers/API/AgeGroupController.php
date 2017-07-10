<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agegroup;

class AgegroupController extends Controller
{
    public function index()
    {
        return Agegroup::all();
    }
}
