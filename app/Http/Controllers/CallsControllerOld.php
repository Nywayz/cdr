<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CallsController extends Controller
{

    public function mainSearch(Request $request)
    {
        $company = '%veloce%';

        $first = DB::connection('wildix901')
            ->table('wms')
            ->select('c_to as Number', 'to_name as Name')
            ->distinct()
            ->whereRaw('LENGTH(`c_to`) = 4')
            ->where('lastdata', 'LIKE', $company)
            ->where('to_name', 'REGEXP', '[A-Z]+[a-z]*');

        $users = DB::connection('wildix901')
            ->table('wms')
            ->select('c_from as Number', 'from_name as Name')
            ->distinct()
            ->whereRaw('LENGTH(`c_from`) = 4')
            ->where('lastdata', 'LIKE', $company)
            ->where('from_name', 'REGEXP', '[A-Z]+[a-z]*')
            ->union($first)
            ->orderBy('Number')
            ->get();

        $groups = DB::connection('wildix901')
            ->table('wms')
            ->select("lastdata as Tag")
            ->distinct()
            ->where('lastdata', 'LIKE', $company)
            ->where('lastdata', 'NOT LIKE', '%entrant%')
            ->where('lastdata', 'NOT LIKE', '%sortant%')
            ->where('lastdata', 'NOT LIKE', '%Entrant%')
            ->where('lastdata', 'NOT LIKE', '%Sortant%')
            ->orderBy('Tag')
            ->get();

        if ($groups[0]->Tag != ""){
            for ($i = 0; $i < count($groups); $i++){
                $groups[$i]->Tag = substr($groups[$i]->Tag, 5);
            }
        }

        return view('cdr/main', ['infos' => $request, 'users' => $users, 'groups' => $groups]);
    }

    public function statistics(Request $request, $mode = 'got')
    {
        //Récupère/Met à jour le mode actuel
        if ($request->mode) { $mode = $request->mode; }

        //Si la date reçue est différente de la date stockée en session, met à jour la date en session.
        if ($request->startInHeader) {
            session(['start' => date_format(date_create_from_format('j/m/Y', $request->startInHeader), 'Y-m-d'), 'end' => date_format(date_create_from_format('j/m/Y', $request->endInHeader), 'Y-m-d')]);
        }

        //Si une date est stockée en session, utilise la date stockée en session. Sinon utilise la date du jour.
        if (session('start', date("Y-m-d"))) {
            $start = session('start', date("Y-m-d"));
            $end = session('end', date("Y-m-d"));
        }
        else {
            $start = new Date();
            $end = $start;
        }

        //Utilise le nom de l'utilisateur comme index pour la recherche. Si aucun utilisateur n'est connecté, l'accès n'est pas permis.
       /* if ( auth()->user() ) {
            $company = auth()->user()->name;
        }
        else {
            return view('/cdr');
        } */

        $company = 'veloce';

        if ($mode == "taken" OR $mode == "spent") {

            $first = DB::connection('wildix901')
                ->table('wms')
                ->select('c_to as Number', 'to_name as Name')
                ->distinct()
                ->whereRaw('LENGTH(`c_to`) = 4')
                ->where('lastdata', 'LIKE', '%'.$company.'%')
                ->where('start', '>', $start)
                ->where('start', '<', date("Y-m-d", strtotime($end . ' +1 day')))
                ->where('to_name', 'REGEXP', '[A-Z]+[a-z]*');

            $selectedUsers = DB::connection('wildix901')
                ->table('wms')
                ->select('c_from as Number', 'from_name as Name')
                ->distinct()
                ->whereRaw('LENGTH(`c_from`) = 4')
                ->where('lastdata', 'LIKE', '%'.$company.'%')
                ->where('start', '>', $start)
                ->where('start', '<', date("Y-m-d", strtotime($end . ' +1 day')))
                ->where('from_name', 'REGEXP', '[A-Z]+[a-z]*')
                ->union($first)
                ->orderBy('Number')
                ->get();
        }
        elseif ($mode == "got" OR $mode == "lost" OR $mode == "time") {
            $groups = DB::connection('wildix901')
                ->table('wms')
                ->select("lastdata as Tag")
                ->distinct()
                ->where('lastdata', 'LIKE', $company)
                ->where('lastdata', 'NOT LIKE', '%entrant%')
                ->where('lastdata', 'NOT LIKE', '%sortant%')
                ->where('lastdata', 'NOT LIKE', '%Entrant%')
                ->where('lastdata', 'NOT LIKE', '%Sortant%')
                ->orderBy('Tag')
                ->get();
        }

        $users = array();
        foreach ($selectedUsers as $selectedUser){

            switch ($mode){
                case "taken":

                    /// //
                    /// Récupération hebomadaire
                    /// //
                    $monday = 0; $tuesday = 0; $wednesday = 0; $thursday = 0; $friday = 0; $saturday = 0; $sunday = 0;
                    $totalD = 0;

                    $timeSpent = DB::connection('wildix901')
                        ->table('wms')
                        ->whereRaw("lastdata like ?", '%'.$company.'%')
                        ->where('start', '>', $start)
                        ->where('start', '<', date("Y-m-d", strtotime($end . ' +1 day')))
                        ->where('c_to', '=', $selectedUser->Number)
                        ->where('billsec', '!=', '0')
                        ->where('disposition', '=', 'answered')
                        ->where("dest_type", "=", 2)
                        ->sum('billsec');

                    for ($i = 0; $i < 7; $i++){
                        $numberOfCallsOnThisDay = DB::connection('wildix901')
                            ->table('wms')
                            ->whereRaw("lastdata like ?", '%'.$company.'%')
                            ->where('start', '>', $start)
                            ->where('start', '<', date("Y-m-d", strtotime($end . ' +1 day')))
                            ->where('c_to', '=', $selectedUser->Number)
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->where("dest_type", "=", 2)
                            ->whereRaw('WEEKDAY(`start`) = ?', $i)
                            ->count();


                        $totalD += $numberOfCallsOnThisDay;
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
                    $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "total" => $totalD);

                    /// //
                    /// Récupération horraire
                    /// //
                    $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;
                    $totalH = 0;
                    for ($i = 0; $i < 24; $i++){
                        $numberOfCallsAtThisHour =  DB::connection('wildix901')
                            ->table('wms')
                            ->whereRaw("lastdata like ?", '%'.$company.'%')
                            ->where('start', '>', $start)
                            ->where('start', '<', date("Y-m-d", strtotime($end . ' +1 day')))
                            ->where('c_to', '=', $selectedUser->Number)
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->where("dest_type", "=", 2)
                            ->whereRaw('HOUR(`start`) = ?', $i)
                            ->count();

                        $totalH += $numberOfCallsAtThisHour;

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

                    $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $totalH);


                    $user = array("number" => $selectedUser->Number, "name" => $selectedUser->Name, "weekdays" => $weekdays, "hours" => $hours, "total" => $timeSpent);
                    $users[$selectedUser->Name] = $user;
                break;
                case "spent":


                    /// //
                    /// Récupération hebomadaire
                    /// //
                    $monday = 0; $tuesday = 0; $wednesday = 0; $thursday = 0; $friday = 0; $saturday = 0; $sunday = 0;
                    $totalD = 0;
                    for ($i = 0; $i < 7; $i++){

                        $timeSpentOnCallsOnThisDay = DB::connection('wildix901')
                            ->table('wms')
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where('c_to', '=', $selectedUser->Number)
                            ->where('to_name', '=', $selectedUser->Name)
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->whereRaw('WEEKDAY(`start`) = ?', $i)
                            ->sum('billsec');

                        $totalD += $timeSpentOnCallsOnThisDay;
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
                        $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "total" => $totalD);

                        /// //
                        /// Récupération horraire
                        /// //
                        $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;
                        $totalH = 0;
                        for ($i = 0; $i < 24; $i++){

                            $timeSpentOnCallsAtThisHour = DB::connection('wildix901')
                                ->table('wms')
                                ->where('start', '>', $start)
                                ->where('start', '<', strtotime($end . ' +1 day'))
                                ->where('c_to', '=', $selectedUser->Number)
                                ->where('to_name', '=', $selectedUser->Name)
                                ->where('billsec', '!=', '0')
                                ->where('disposition', '=', 'answered')
                                ->whereRaw('HOUR(`start`) = ?', $i)
                                ->sum('billsec');

                            $totalH += $timeSpentOnCallsAtThisHour;

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
                    $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $totalH);
                    $user = array("number" => $selectedUser->Number, "name" => $selectedUser->Name, "weekdays" => $weekdays, "hours" => $hours);
                    $users[$selectedUser->Name] = $user;
                break;
                case "got":

                    $selectedUser->Number = "";
                    $selectedUser->Name = "";

                    /// //
                    /// Récupération hebomadaire
                    /// //
                    $monday = 0; $tuesday = 0; $wednesday = 0; $thursday = 0; $friday = 0; $saturday = 0; $sunday = 0;
                    $totalD = 0;
                    for ($i = 0; $i < 7; $i++){
                        $numberOfCallsOnThisDay = DB::connection('wildix901')
                            ->table('wms')
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where("dest_type", "=", 2)
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->where("dest_type", "=", 2)
                            ->whereRaw('WEEKDAY(`start`) = ?', $i)
                            ->count();


                        $totalD += $numberOfCallsOnThisDay;
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
                    $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "total" => $totalD);

                    /// //
                    /// Récupération horraire
                    /// //
                    $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;
                    $totalH = 0;
                    for ($i = 0; $i < 24; $i++){
                        $numberOfCallsAtThisHour =  DB::connection('wildix901')
                            ->table('wms')
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where("dest_type", "=", 2)
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->where("dest_type", "=", 2)
                            ->whereRaw('HOUR(`start`) = ?', $i)
                            ->count();

                        $totalH += $numberOfCallsAtThisHour;

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

                    $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $totalH);


                    $user = array("number" => $selectedUser->Number, "name" => $selectedUser->Name, "complete" => $selectedUser, "weekdays" => $weekdays, "hours" => $hours);
                    $users[$selectedUser->Name] = $user;
                break;
                case "lost":

                    $selectedUser->Number = "";
                    $selectedUser->Name = "";

                    /// //
                    /// Récupération hebomadaire
                    /// //
                    $monday = 0; $tuesday = 0; $wednesday = 0; $thursday = 0; $friday = 0; $saturday = 0; $sunday = 0;
                    $totalD = 0;
                    for ($i = 0; $i < 7; $i++){
                        $numberOfCallsOnThisDay = DB::connection('wildix901')
                            ->table('wms')
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where("dest_type", "=", 2)
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->whereRaw('(duration - billsec) >= 30')
                            ->where("disposition", "=", 'NO ANSWER')
                            ->whereRaw('WEEKDAY(`start`) = ?', $i)
                            ->count();


                        $totalD += $numberOfCallsOnThisDay;
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
                    $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "total" => $totalD);

                    /// //
                    /// Récupération horraire
                    /// //
                    $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;
                    $totalH = 0;
                    for ($i = 0; $i < 24; $i++){
                        $numberOfCallsAtThisHour =  DB::connection('wildix901')
                            ->table('wms')
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where("dest_type", "=", 2)
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->whereRaw('(duration - billsec) >= 30')
                            ->where("disposition", "=", 'NO ANSWER')
                            ->whereRaw('HOUR(`start`) = ?', $i)
                            ->count();

                        $totalH += $numberOfCallsAtThisHour;

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

                    $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $totalH);


                    $user = array("number" => $selectedUser->Number, "name" => $selectedUser->Name, "complete" => $selectedUser, "weekdays" => $weekdays, "hours" => $hours);
                    $users[$selectedUser->Name] = $user;
                    break;
                case "time":

                    $selectedUser->Number = "";
                    $selectedUser->Name = "";

                    /// //
                    /// Récupération hebomadaire
                    /// //
                    $monday = 0; $tuesday = 0; $wednesday = 0; $thursday = 0; $friday = 0; $saturday = 0; $sunday = 0;
                    $totalD = 0;
                    for ($i = 0; $i < 7; $i++){

                        $billsec = DB::connection('wildix901')
                            ->table('wms')
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where("dest_type", "=", 2)
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->whereRaw('WEEKDAY(`start`) = ?', $i)
                            ->avg('billsec');

                        $duration = DB::connection('wildix901')
                            ->table('wms')
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where("dest_type", "=", 2)
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->whereRaw('WEEKDAY(`start`) = ?', $i)
                            ->avg('duration');

                        $timeSpentOnCallsOnThisDay = $duration - $billsec;

                        $totalD += $timeSpentOnCallsOnThisDay;
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
                    $weekdays = array("monday" => $monday, "tuesday" => $tuesday, "wednesday" => $wednesday, "thursday" => $thursday, "friday" => $friday, "saturday" => $saturday, "sunday" => $sunday, "total" => $totalD);

                    /// //
                    /// Récupération horraire
                    /// //
                    $midnight = 0; $one = 0; $two = 0; $three = 0; $four = 0; $five = 0; $four = 0; $six = 0; $seven = 0; $eight = 0; $nine = 0; $ten = 0; $eleven = 0; $twelve = 0; $thirteen = 0; $fourteen = 0; $fifteen = 0; $sixteen = 0; $seventeen = 0; $eighteen = 0; $nineteen = 0; $twenty = 0; $twentyOne = 0; $twentyTwo = 0; $twentyThree = 0;
                    $totalH = 0;
                    for ($i = 0; $i < 24; $i++){

                        $billsec = DB::connection('wildix901')
                            ->table('wms')
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where("dest_type", "=", 2)
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->whereRaw('HOUR(`start`) = ?', $i)
                            ->avg('billsec');

                        $duration = DB::connection('wildix901')
                            ->table('wms')
                            ->where('start', '>', $start)
                            ->where('start', '<', strtotime($end . ' +1 day'))
                            ->where("dest_type", "=", 2)
                            ->whereRaw("lastdata like ?", '%'.$selectedUser.'%')
                            ->where('billsec', '!=', '0')
                            ->where('disposition', '=', 'answered')
                            ->whereRaw('HOUR(`start`) = ?', $i)
                            ->avg('duration');

                        $timeSpentOnCallsAtThisHour = $duration - $billsec;

                        $totalH += $timeSpentOnCallsAtThisHour;

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
                    $hours = array("midnight" => $midnight, "one" => $one, "two" => $two, "three" => $three, "four" => $four, "five" => $five,  "six" => $six, "seven" => $seven, "eight" => $eight, "nine" => $nine, "ten" => $ten, "eleven" => $eleven, "twelve" => $twelve, "thirteen" => $thirteen, "fourteen" => $fourteen, "fifteen" => $fifteen, "sixteen" => $sixteen, "seventeen" => $seventeen, "eighteen" => $eighteen, "nineteen" => $nineteen, "twenty" => $twenty, "twentyOne" => $twentyOne, "twentyTwo" => $twentyTwo, "twentyThree" => $twentyThree, "total" => $totalH);
                    $user = array("number" => $selectedUser->Number, "name" => $selectedUser->Name, "complete" => $selectedUser, "weekdays" => $weekdays, "hours" => $hours);
                    $users[$selectedUser->Name] = $user;
                    break;

            }
        }

        return view('cdr/stats', ['infos' => $request, 'users' => $users, 'start' => session('start', date("Y-m-d")), 'end' => session('end', date("Y-m-d")), 'mode' => $mode]);
    }

    public function statsZoom($name, $mode = "",$length = "", $filter = "")
    {
        $company = '%veloce%';

        $explodedName = explode('§', $name);
        $explodedDate = explode('§', $length);

        if ($mode == "weekday"){
            $cdrs = DB::connection('wildix901')
                ->table('wms')
                ->whereRaw("lastdata like ?", '%'.$company.'%')
                ->where('start', '>', $explodedDate[0])
                ->where('start', '<', $explodedDate[1])
                ->where('c_to', '=', $explodedName[0])
                ->where('to_name', '=', $explodedName[1])
                ->where('billsec', '!=', '0')
                ->where('disposition', '=', 'answered')
                ->whereRaw('WEEKDAY(`start`) = ?', $filter)
                ->get();
        }

    }

    public function getCallById($id)
    {
        $cdrs = DB::connection('wildix901')
            ->table('wms')
            ->where('rowid', '=', $id)
            ->orderBy('start')
            ->get();


        return view('cdr/lastMinutes', ['cdrs' => $cdrs, 'now' => date('Y-m-d H:i:s', strtotime("now +2 hours -10 minutes"))]);
    }



}
