<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RequetesController extends Controller
{
    public function getLastMinutes($database = 'wildix02', $mode = "", $min = 10, $filter = "")
    {
        if ($filter != ""){
            $cdrs = DB::connection($database)
                ->table('wms')
                ->where('lastdata', 'like', '%'.$filter.'%')
                ->where('start', '>', date('Y-m-d H:i:s', strtotime("now +2 hours -".$min." minutes")))
                ->orderBy('start')
                ->get();
        }
        else {
            $cdrs = DB::connection($database)
                ->table('wms')
                ->where('start', '>', date('Y-m-d H:i:s', strtotime("now +2 hours -".$min." minutes")))
                ->orderBy('start')
                ->get();
        }

        if ($mode = "all"){
            $infos = array('database' => $database, 'mode' => $mode, 'min' => $min, 'filter' => $filter);
            return view('cdr/lastMinutesAll', ['cdrs' => $cdrs, 'infos' => $infos]);
        }

        return view('cdr/lastMinutes', ['cdrs' => $cdrs, 'now' => date('Y-m-d H:i:s', strtotime("now +2 hours -".$min." minutes"))]);
    }



}
