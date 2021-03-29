<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cdr', function () {

    $clientName = 'veloce';
    $client = '%'.$clientName.'%';

        // Récupération de noms.
        $cdrs = DB::table('wms')
            ->where('start', '>', '2021-01-01')
            ->whereRaw("lastdata like ?", $client)
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
                    $numberOfCallsOnThisDay = DB::table('wms')
                        ->whereRaw("lastdata like ?", $client)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('WEEKDAY(`start`) = ?', $i)
                        ->count();
                    $total += $numberOfCallsOnThisDay;
                    switch ($i) {
                        case 0:
                            $monday = array('number' => $numberOfCallsOnThisDay);
                            break;
                        case 1:
                            $tuesday = array('number' => $numberOfCallsOnThisDay);
                            break;
                        case 2:
                            $wednesday = array('number' => $numberOfCallsOnThisDay);
                            break;
                        case 3:
                            $thursday = array('number' => $numberOfCallsOnThisDay);
                            break;
                        case 4 :
                            $friday = array('number' => $numberOfCallsOnThisDay);
                            break;
                        case 5:
                            $saturday = array('number' => $numberOfCallsOnThisDay);
                            break;
                        case 6:
                            $sunday = array('number' => $numberOfCallsOnThisDay);
                    }
                }

                $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday);

                /// //
                /// Récupération horraire
                /// //
                $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;

               $total = 0;
                for ($i = 0; $i < 24; $i++){
                    $numberOfCallsAtThisHour = DB::table('wms')
                        ->whereRaw("lastdata like ?", $client)
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('HOUR(`start`) = ?', $i)
                        ->count();
                    $total += $numberOfCallsAtThisHour;
                    switch ($i){
                        case 0:
                            $midnight = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 1:
                            $one = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 2:
                            $two = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 3:
                            $three = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 4:
                            $four = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 5:
                            $five = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 6:
                            $six = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 7:
                            $seven = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 8:
                            $eight = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 9:
                            $nine = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 10:
                            $ten = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 11:
                            $eleven = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 12:
                            $twelve = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 13:
                            $thirteen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 14:
                            $fourteen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 15:
                            $fifteen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 16:
                            $sixteen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 17:
                            $seventeen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 18:
                            $eighteen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 19:
                            $nineteen = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 20:
                            $twenty = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 21:
                            $twentyOne = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 22:
                            $twentyTwo = array('number' => $numberOfCallsAtThisHour);
                            break;
                        case 23:
                            $twentyThree = array('number' => $numberOfCallsAtThisHour);
                            break;

                    }
                }

                $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $total);



                //




                /// //
                /// Mise en base
                /// //

                array_push($users, array("id" => $cdr->c_from, "name" => $cdr->from_name, "weekdays" => $weekdays, "hours" => $hours, "total" => $total));
                $userList[$cdr->c_from]= $cdr->from_name;






            }
        }


        ///
    ///
    /// Passage aux utilisateurs n'ayant fait que des appels sortants
    ///
        ///


     /*   $cdrs = DB::table('wms')
            ->whereRaw("lastdata like ?", $client)
            ->where('start', '>', '2021-01-01')
            ->orderBy('c_to')
            ->get();

        foreach ($cdrs as $cdr){
            if (!str_contains($cdr->c_to, '+') and !str_contains($cdr->to_name, '+') and $cdr->c_to != '' and $cdr->to_name != '' and array_key_exists($cdr->c_to, $userList) == false){
                array_push($users, array("id" => $cdr->c_to, "name" => $cdr->to_name));
                $userList[$cdr->c_to] = $cdr->to_name;
            }
        }
    */
        // Récupération du nombre d'appels entrant par usager.



    return view('cdr', ['cdrs' => $cdrs, 'users' => $users]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
