<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index()
    {
        return view('computer');
    }


    public function uploadimage(Request $request)
    {
        
    }
}
