<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        return view('admin.bookings.index');
    }

    public function create()
    {
        return view('admin.bookings.create');
    }

    public function store()
    {
        
    }

    public function show($id)
    {
        return view('admin.bookings.show');
    }

    public function edit($id)
    {
        return view('admin.bookings.edit');
    }

    public function update($id)
    {
        
    }

    public function konfirmasi($id)
    {
        
    }

    public function tolak($id)
    {
        
    }
}