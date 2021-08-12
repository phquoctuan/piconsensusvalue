<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DonateLog;

class DonateLogController extends Controller
{
    public function index(Request $request)
    {
        $items = DonateLog::whereNotNull('draw_date')->latest('created_at')->paginate(20);
        if ($request->ajax()) {
            return view('donatelog.donatelog_item', ['items' => $items])->render();
        }
        return view('donatelog.index', compact('items'));
    }
}
