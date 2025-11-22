<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function schedule()
    {
        return view('page.schedule');
    }

    public function manage()
    {
        return view('page.manage');
    }
}
