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

        // Récupération de noms.
        $cdrs = DB::table('wms')
            ->where('lastdata', 'like', '%veloce%')
            ->where('start', '>', '2021-01-01')
            ->orderBy('c_from')
            ->get();

        $users = array();
        $userList = array();

        foreach ($cdrs as $cdr){
            if (!str_contains($cdr->c_from, '+') and !str_contains($cdr->from_name, '+') and $cdr->c_from != '' and $cdr->from_name != '' and array_key_exists($cdr->c_from, $userList) == false){

                /////
                /// Récupération des jours de la semaine
                /////

                    $numberOfCallsOnMondays = DB::table('wms')
                        ->where('lastdata', 'like', '%veloce%')
                        ->where('start', '>', '2021-01-01')
                        ->where('start', '<', '2021-01-31')
                        ->where('c_to', '=', $cdr->c_from)
                        ->where('disposition', '=', 'answered')
                        ->whereRaw('WEEKDAY(`start`) = ?','0')
                        ->count();

                    $monday = array('number' => $numberOfCallsOnMondays);

                    //

                    $numberOfCallsOnTuesdays = DB::table('wms')->where('lastdata', 'like', '%veloce%')->where('start', '>', '2021-01-01')->where('start', '<', '2021-01-31')->where('c_to', '=', $cdr->c_from)->where('disposition', '=', 'answered') ->whereRaw('WEEKDAY(`start`) = ?','1')->count();

                    $tuesday = array('number' => $numberOfCallsOnTuesdays);

                    //

                    $numberOfCallsOnWednesdays = DB::table('wms')->where('lastdata', 'like', '%veloce%')->where('start', '>', '2021-01-01')->where('start', '<', '2021-01-31')->where('c_to', '=', $cdr->c_from)->where('disposition', '=', 'answered') ->whereRaw('WEEKDAY(`start`) = ?','2')->count();

                    $wednesday = array('number' => $numberOfCallsOnWednesdays);

                    //

                    $numberOfCallsOnThursdays = DB::table('wms')->where('lastdata', 'like', '%veloce%')->where('start', '>', '2021-01-01')->where('start', '<', '2021-01-31')->where('c_to', '=', $cdr->c_from)->where('disposition', '=', 'answered') ->whereRaw('WEEKDAY(`start`) = ?','3')->count();

                    $thursday = array('number' => $numberOfCallsOnThursdays);

                    //

                    $numberOfCallsOnFridays = DB::table('wms')->where('lastdata', 'like', '%veloce%')->where('start', '>', '2021-01-01')->where('start', '<', '2021-01-31')->where('c_to', '=', $cdr->c_from)->where('disposition', '=', 'answered') ->whereRaw('WEEKDAY(`start`) = ?','4')->count();

                    $friday = array('number' => $numberOfCallsOnFridays);

                    //

                    $numberOfCallsOnSaturdays = DB::table('wms')->where('lastdata', 'like', '%veloce%')->where('start', '>', '2021-01-01')->where('start', '<', '2021-01-31')->where('c_to', '=', $cdr->c_from)->where('disposition', '=', 'answered') ->whereRaw('WEEKDAY(`start`) = ?','5')->count();

                    $saturday = array('number' => $numberOfCallsOnSaturdays);

                    //

                    $numberOfCallsOnSundays = DB::table('wms')->where('lastdata', 'like', '%veloce%')->where('start', '>', '2021-01-01')->where('start', '<', '2021-01-31')->where('c_to', '=', $cdr->c_from)->where('disposition', '=', 'answered') ->whereRaw('WEEKDAY(`start`) = ?','6')->count();

                    $sunday = array('number' => $numberOfCallsOnSundays);


                /// //
                /// Mise en base
                /// //

                $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday);
                array_push($users, array("id" => $cdr->c_from, "name" => $cdr->from_name, "weekdays" => $weekdays));
                $userList[$cdr->c_from]= $cdr->from_name;






            }
        }


        ///
    ///
    /// Passage aux utilisateurs n'ayant fait que des appels sortants
    ///
        ///


     /*   $cdrs = DB::table('wms')
            ->where('lastdata', 'like', '%veloce%')
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
