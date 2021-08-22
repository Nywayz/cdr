<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CdrController extends Controller
{
    public function callsIndividual()
    {

        $client = 'veloce';
        $base = 'wildix02';
        $table = 'wms';

        // Récupération de noms.
        $cdrs = DB::connection($base)
            ->table($table)
            ->distinct('c_to')
            ->where('start', '>', '2021-01-01')
            ->whereRaw("dcontext like ?", '%'.$client.'%')
            ->orderBy('c_from')
            ->get();

        $users = array();
        $userList = array();
        $grandTotal = 0;

        foreach ($cdrs as $cdr){
            if (!str_contains($cdr->c_from, '+') and !str_contains($cdr->from_name, '+') and !str_contains($cdr->from_name, 'Anonymous') and $cdr->c_from != '' and $cdr->from_name != '' and array_key_exists($cdr->c_from, $userList) == false){

                /////
                /// Récupération des jours de la semaine
                /////

                $total = 0;
                for ($i = 0; $i < 7; $i++){
                    $numberOfCallsOnThisDay = DB::connection($base)
                        ->table($table)
                        ->whereRaw("lastdata like ?", '%'.$client.'%')
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('billsec', '!=', '0')
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('WEEKDAY(`start`) = ?', $i)
                        ->count();

                    $timeSpentOnCallsOnThisDay1 = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->whereRaw('WEEKDAY(`start`) = ?', $i)
                        ->sum('billsec');

                    $timeSpentOnCallsOnThisDay2 = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_from', '=', $cdr->c_from)
                        ->whereRaw('WEEKDAY(`start`) = ?', $i)
                        ->sum('billsec');

                    $timeSpentOnCallsOnThisDay = $timeSpentOnCallsOnThisDay1 + $timeSpentOnCallsOnThisDay2;

                    $total += $numberOfCallsOnThisDay;
                    switch ($i) {
                        case 0:
                            $monday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 1:
                            $tuesday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 2:
                            $wednesday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 3:
                            $thursday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 4 :
                            $friday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 5:
                            $saturday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 6:
                            $sunday = array('number' => $numberOfCallsOnThisDay, 'spent' => $timeSpentOnCallsOnThisDay);
                    }
                }

                $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "total" => $total);

                /// //
                /// Récupération horraire
                /// //
                $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;

                $total = 0;
                for ($i = 0; $i < 24; $i++){
                    $numberOfCallsAtThisHour = DB::connection($base)
                        ->table($table)
                        ->whereRaw("lastdata like ?", '%'.$client.'%')
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('billsec', '!=', '0')
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('HOUR(`start`) = ?', $i)
                        ->count();

                    $timeSpentOnCallsAtThisHour1 = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('HOUR(`start`) = ?', $i)
                        ->sum('billsec');

                    $timeSpentOnCallsAtThisHour2 = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_from', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('HOUR(`start`) = ?', $i)
                        ->sum('billsec');

                    $timeSpentOnCallsAtThisHour = $timeSpentOnCallsAtThisHour1 + $timeSpentOnCallsAtThisHour2;

                    switch ($i){
                        case 0:
                            $midnight = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 1:
                            $one = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 2:
                            $two = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 3:
                            $three = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 4:
                            $four = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 5:
                            $five = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 6:
                            $six = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 7:
                            $seven = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 8:
                            $eight = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 9:
                            $nine = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 10:
                            $ten = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 11:
                            $eleven = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 12:
                            $twelve = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 13:
                            $thirteen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 14:
                            $fourteen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 15:
                            $fifteen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 16:
                            $sixteen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 17:
                            $seventeen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 18:
                            $eighteen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 19:
                            $nineteen = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 20:
                            $twenty = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 21:
                            $twentyOne = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 22:
                            $twentyTwo = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 23:
                            $twentyThree = array('number' => $numberOfCallsAtThisHour, 'spent' => $timeSpentOnCallsAtThisHour);
                            break;

                    }
                }

                $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $total);

                /// //
                /// Mise en base
                /// //

                array_push($users, array("id" => $cdr->c_from, "name" => $cdr->from_name, "weekdays" => $weekdays, "hours" => $hours, "total" => $total));
                $userList[$cdr->c_from]= $cdr->from_name;

            }
        }
        return view('cdr/callsIndividual', ['cdrs' => $cdrs, 'users' => $users]);
    }


    public function timeIndividual()
    {

        $client = 'veloce';
        $base = 'wildix02';
        $table = 'wms';

        // Récupération de noms.
        $cdrs = DB::connection($base)
            ->table($table)
            ->distinct('c_to')
            ->where('start', '>', '2021-01-01')
            ->whereRaw("dcontext like ?", '%'.$client.'%')
            ->orderBy('c_from')
            ->get();

        $users = array();
        $userList = array();
        $grandTotal = 0;

        foreach ($cdrs as $cdr){
            if (!str_contains($cdr->c_from, '+') and !str_contains($cdr->from_name, '+') and !str_contains($cdr->from_name, 'Anonymous') and $cdr->c_from != '' and $cdr->from_name != '' and array_key_exists($cdr->c_from, $userList) == false){

                /////
                /// Récupération des jours de la semaine
                /////


                $totalSpent = 0;
                for ($i = 0; $i < 7; $i++){

                    $timeSpentOnCallsOnThisDay = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->whereRaw('WEEKDAY(`start`) = ?', $i)
                        ->sum('billsec');

                    $timeSpentOnCallsOnThisDay2 = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_from', '=', $cdr->c_from)
                        ->whereRaw('WEEKDAY(`start`) = ?', $i)
                        ->sum('billsec');


                    $totalSpent += $timeSpentOnCallsOnThisDay;
                    switch ($i) {
                        case 0:
                            $monday = array('spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 1:
                            $tuesday = array('spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 2:
                            $wednesday = array('spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 3:
                            $thursday = array('spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 4 :
                            $friday = array('spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 5:
                            $saturday = array('spent' => $timeSpentOnCallsOnThisDay);
                            break;
                        case 6:
                            $sunday = array('spent' => $timeSpentOnCallsOnThisDay);
                    }
                }

                $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "totalSpent" => $totalSpent);

                /// //
                /// Récupération horraire
                /// //
                $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;

                $totalSpent = 0;
                for ($i = 0; $i < 24; $i++){

                    $timeSpentOnCallsAtThisHour = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('HOUR(`start`) = ?', $i)
                        ->sum('billsec');

                    $timeSpentOnCallsAtThisHour2 = DB::connection($base)
                        ->table($table)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_from', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('HOUR(`start`) = ?', $i)
                        ->sum('billsec');

                    $totalSpent += $timeSpentOnCallsAtThisHour;
                    switch ($i){
                        case 0:
                            $midnight = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 1:
                            $one = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 2:
                            $two = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 3:
                            $three = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 4:
                            $four = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 5:
                            $five = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 6:
                            $six = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 7:
                            $seven = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 8:
                            $eight = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 9:
                            $nine = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 10:
                            $ten = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 11:
                            $eleven = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 12:
                            $twelve = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 13:
                            $thirteen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 14:
                            $fourteen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 15:
                            $fifteen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 16:
                            $sixteen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 17:
                            $seventeen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 18:
                            $eighteen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 19:
                            $nineteen = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 20:
                            $twenty = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 21:
                            $twentyOne = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 22:
                            $twentyTwo = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;
                        case 23:
                            $twentyThree = array('spent' => $timeSpentOnCallsAtThisHour);
                            break;

                    }
                }

                $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree,"totalSpent" => $totalSpent);

                /// //
                /// Mise en base
                /// //

                array_push($users, array("id" => $cdr->c_from, "name" => $cdr->from_name, "weekdays" => $weekdays, "hours" => $hours, "totalSpent" =>$totalSpent));
                $userList[$cdr->c_from]= $cdr->from_name;

            }
        }
        return view('cdr/timeIndividual', ['cdrs' => $cdrs, 'users' => $users]);
    }



}
