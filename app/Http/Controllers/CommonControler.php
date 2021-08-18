<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonControler extends Controller
{
    public function about()
    {
        return view('Common.about');
    }
}
