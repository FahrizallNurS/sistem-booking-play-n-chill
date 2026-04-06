<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataUserController extends Controller
{
    public function index()
    {
        // Memanggil file resources/views/superadmin/datauser.blade.php
        return view('superadmin.datauser');
    }
}