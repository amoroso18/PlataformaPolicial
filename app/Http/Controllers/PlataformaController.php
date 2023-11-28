<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlataformaController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('modules.dashboard.index');
    }
}
