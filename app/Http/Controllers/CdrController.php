<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CdrController extends Controller
{
    public function index()
    {
        $cdrs = DB::table('cdr')->get();

        return view('cdrs', ['users' => $users]);
    }
}
