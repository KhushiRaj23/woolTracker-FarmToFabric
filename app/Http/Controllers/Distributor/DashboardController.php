<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:distributor');
    }

    //
    public function index(){
    return view('distributor.dashboard');
    }
    
}
