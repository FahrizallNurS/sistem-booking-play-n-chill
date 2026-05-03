<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class GameController extends Controller
{
    public function index()
    {
        return view('admin.game.index');
    }
}