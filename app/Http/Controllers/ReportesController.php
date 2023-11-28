<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Models\TipoGrado;
use App\Models\TipoPerfil;
use App\Models\TipoUnidad;
use App\Http\Controllers\AuditoriaController;

use Codedge\Fpdf\Fpdf\Fpdf;

class ReportesController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function dashboard()
    {
        return view('modules.dashboard.index');
    }

  
}
