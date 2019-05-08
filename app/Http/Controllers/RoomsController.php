<?php

namespace App\Http\Controllers;

use App\Poll;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Poll::distinct('room')->get();
        return view('rooms.index', compact('rooms'));
    }
}
