<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Event;

class HomeController extends Controller
{
    public function home()
    {
        $events = Event::where('status', Event::APPROVED)->get();

        return view('home.home')->with('events', $events);
    }
}
