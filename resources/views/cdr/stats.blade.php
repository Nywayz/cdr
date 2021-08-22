<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Cdr</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    <style>
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        tr, td, th {
            text-align: center;
            border: solid black 1px;
        }
    </style>

    <!-- Google Chart -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Nom', 'Nombre'],
                @foreach($users as $user)
                    ['{{ $user['number'].' '.$user['name'] }}', {{ $user['weekdays']['total'] }}],
                @endforeach
            ]);

            var options = {
                title: 'My Daily Activities'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>



</head>
<body class="antialiased bg-epmLighter">
<?php

    $classement = 0;

    switch ($infos->group) {
        case 'taken':
            $context = "Nombre d'appels entrants pris";
            $contextBis = "Classement de la prise d'appels entrant";
            break;

        case 'spent':
            $context = "Temps passé en mins au téléphone (entrants et sortants)";
            $contextBis = "Classement du temps passé en mins au téléphone (entrants et sortants)";
            break;

        case 'got':
            $context = "Nombre d'appels entrants traités";
            $contextBis = "Classement du traitement des appels entrants";
            break;

        case 'time':
            $context = "Temps moyen de décrochage (secondes) sur chaque groupe";
            $contextBis = "Classement du temps moyen de décrochage (secondes)";
            break;

        case 'lost':
            $context = "Nombre d'appels entrants non répondus";
            $contextBis = "Classement du nombre d appels entrants non répondus";
            break;

        default:
            $context = "/";
            $contextBis = "/";
            break;
    }

    function cmp($a, $b)
    {
        return $b['total'] <=> $a['total'];
    }
?>

<div class="bg-epmLighter text-black mt-16 ml-36 right-0 bottom-0 pr-20 pl-20 pt-10 pb-20 flex flex-wrap justify-self-center">


    <div class="w-full">
        <h2 class="font-bold">
            {{ $context }} par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}
        </h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-none"></th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">8h - 9h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">9h - 10h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">10h - 11h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">11h - 12h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">12h - 13h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">13h - 14h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">14h - 15h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">15h - 16h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">16h - 17h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">17h - 18h</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">18h - 19h</th>
                @if($infos->group != "time")<th style="padding: 5px 10px" class="border-black border-1 bg-white">H. plages</th>@endif
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="border-black border-1 bg-white">{{ $user['complete']  }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eight']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['nine']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['ten']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eleven']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['twelve']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['thirteen']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['fourteen']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['fifteen']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['sixteen']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['seventeen']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eighteen']['number'], 0) }}</td>
                    @if($infos->group != "time")<td class="pl-3 pr-3 bg-white">{{ round($user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['nineteen']['number'] + $user['hours']['nineteen']['number'] + $user['hours']['twentyThree']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'], 0) }}</td>@endif
                    <td class="pl-3 pr-3 bg-white"><?php $total = $user['hours']['total']; if($infos->group == "time" && $user['hours']['notAtZero'] != 0){$total = $total / $user['hours']['notAtZero'];} ?> {{ round($total , 0) }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; $notAtZero = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['number']; if ($infos->group == "time" && $user['hours']['eight']['number'] != "0") { $notAtZero += 1;}}              if ($infos->group == "time" && $notAtZero != 0) { $total1 = $total1 / $notAtZero; }echo round($total1, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; $notAtZero = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['number']; if ($infos->group == "time" && $user['hours']['nine']['number'] != "0") { $notAtZero += 1;}}                if ($infos->group == "time" && $notAtZero != 0) { $total2 = $total2 / $notAtZero; }echo round($total2, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; $notAtZero = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['number']; if ($infos->group == "time" && $user['hours']['ten']['number'] != "0") { $notAtZero += 1;}}                  if ($infos->group == "time" && $notAtZero != 0) { $total3 = $total3 / $notAtZero; }echo round($total3, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; $notAtZero = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['number']; if ($infos->group == "time" && $user['hours']['eleven']['number'] != "0") { $notAtZero += 1;}}            if ($infos->group == "time" && $notAtZero != 0) { $total4 = $total4 / $notAtZero; }echo round($total4, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; $notAtZero = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['number']; if ($infos->group == "time" && $user['hours']['twelve']['number'] != "0") { $notAtZero += 1;}}            if ($infos->group == "time" && $notAtZero != 0) { $total5 = $total5 / $notAtZero; }echo round($total5, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; $notAtZero = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['number']; if ($infos->group == "time" && $user['hours']['thirteen']['number'] != "0") { $notAtZero += 1;}}        if ($infos->group == "time" && $notAtZero != 0) { $total6 = $total6 / $notAtZero; }echo round($total6, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; $notAtZero = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['number']; if ($infos->group == "time" && $user['hours']['fourteen']['number'] != "0") { $notAtZero += 1;}}        if ($infos->group == "time" && $notAtZero != 0) { $total7 = $total7 / $notAtZero; }echo round($total7, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total8 = 0; $notAtZero = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['number']; if ($infos->group == "time" && $user['hours']['fifteen']['number'] != "0") { $notAtZero += 1;}}          if ($infos->group == "time" && $notAtZero != 0) { $total8 = $total8 / $notAtZero; }echo round($total8, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total9 = 0; $notAtZero = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['number']; if ($infos->group == "time" && $user['hours']['sixteen']['number'] != "0") { $notAtZero += 1;}}          if ($infos->group == "time" && $notAtZero != 0) { $total9 = $total9 / $notAtZero; }echo round($total9, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total10 = 0; $notAtZero = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['number']; if ($infos->group == "time" && $user['hours']['seventeen']['number'] != "0") { $notAtZero += 1;}}    if ($infos->group == "time" && $notAtZero != 0) { $total10 = $total10 / $notAtZero; }echo round($total10, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total11 = 0; $notAtZero = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['number']; if ($infos->group == "time" && $user['hours']['eighteen']['number'] != "0") { $notAtZero += 1;}}      if ($infos->group == "time" && $notAtZero != 0) { $total11 = $total11 / $notAtZero; }echo round($total11, 0); ?></td>
            @if($infos->group != "time")<td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total12 = 0; $notAtZero = 0; foreach ($users as $user){ $total12 += $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $total12 += $user['hours']['nineteen']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'];} if ($infos->group == "time" && $total12 != "0") { $notAtZero += 1;} echo round($total12, 0); ?></td>@endif
            @if($infos->group != "time")<td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ round($total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12, 0) }}</td>@endif
        </table><br/>
    </div>
    <div class="w-full flex p-0">
        <div class="w-1/2">
        <h2 class="font-bold">{{ $context }} par jour entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-none"></th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">L</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">M</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">M</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">J</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">V</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">S</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">D</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="border-black border-1 bg-white">{{ $user['complete']  }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['monday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['tuesday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['wednesday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['thursday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['friday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['saturday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['sunday']['number'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white"><?php $total = $user['weekdays']['total']; if($infos->group == "time" && $user['weekdays']['notAtZero'] != 0){$total = $total / $user['weekdays']['notAtZero'];} ?> {{ round($total , 0) }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; $notAtZero = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['number'];    if ($infos->group == "time" && $user['weekdays']['monday']['number'] != "0") { $notAtZero += 1;}}   if ($infos->group == "time" && $notAtZero != 0) { $total1 = $total1 / $notAtZero; }    echo round($total1, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; $notAtZero = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['number'];   if ($infos->group == "time" && $user['weekdays']['tuesday']['number'] != "0") { $notAtZero += 1;}}  if ($infos->group == "time" && $notAtZero != 0) { $total2 = $total2 / $notAtZero; }    echo round($total2, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; $notAtZero = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['number']; if ($infos->group == "time" && $user['weekdays']['wednesday']['number'] != "0") { $notAtZero += 1;}}if ($infos->group == "time" && $notAtZero != 0) { $total3 = $total3 / $notAtZero; }    echo round($total3, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; $notAtZero = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['number'];  if ($infos->group == "time" && $user['weekdays']['thursday']['number'] != "0") { $notAtZero += 1;}} if ($infos->group == "time" && $notAtZero != 0) { $total4 = $total4 / $notAtZero; }    echo round($total4, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; $notAtZero = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['number'];    if ($infos->group == "time" && $user['weekdays']['friday']['number'] != "0") { $notAtZero += 1;}}   if ($infos->group == "time" && $notAtZero != 0) { $total5 = $total5 / $notAtZero; }    echo round($total5, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; $notAtZero = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['number'];  if ($infos->group == "time" && $user['weekdays']['saturday']['number'] != "0") { $notAtZero += 1;}} if ($infos->group == "time" && $notAtZero != 0) { $total6 = $total6 / $notAtZero; }    echo round($total6, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; $notAtZero = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['number'];    if ($infos->group == "time" && $user['weekdays']['sunday']['number'] != "0") { $notAtZero += 1;}}   if ($infos->group == "time" && $notAtZero != 0) { $total7 = $total7 / $notAtZero; }    echo round($total7, 0); ?></td>
            @if($infos->group != "time")<td style="padding: 5px 10px" class="border-black border-1 bg-white"> <?php $total = $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7; ?>{{ round($total, 0) }}</td>@endif
        </table>
    </div>
    <div class="w-1/2">
        <?php usort($users, "cmp"); ?>

        <h2 class="font-bold"> {{ $contextBis ?? 'oui' }} entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $classement = $classement + 1 }}</td>
                    <td class="border-black border-1 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['total'], 0) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    </div>






    </div>

    <form class="bg-epm text-epmLight fixed w-full top-0 h-16 flex flex-column text-3xl items-center" action="{{ $mode }}">
        @method("post")
        <img class="h-16" src="{{asset('img\logo-bleu.png')}}">
        <div class="text-lg leading-none space-x-2">Vue <br/> CDR</div>
        <div class="flex flex-column space-x-5 ml-20 items-center">
            <input type="text" name="startInHeader" id="startInHeader" value="{{date('j/m/Y', strtotime($start))}}" class="bg-epm text-3xl border-none w-48" readonly>
            <div> - </div>
            <input type="text" name="endInHeader" id="endInHeader" value="{{date('j/m/Y', strtotime($end))}}" class="bg-epm text-3xl border-none w-48" readonly>
            <div class="bg-epmLighter flex items-center justify-center rounded-full w-6 h-6" onclick="openSelector()"><img class="h-4" src="{{asset('img\calendar.svg')}}"></div>
            <div class="bg-epmLighter flex items-center justify-center rounded-full w-6 h-6"><button><img class="h-4" src="{{asset('img\next.svg')}}"></button></div>
        </div>
        <div class="ml-auto mr-4">
            @auth
                Bonjour, {{ auth()->user()->name }}
            @else
                <a href="{{ route('login') }}">Se connecter</a>
            @endauth

        </div>
    </form>

    <div class="bg-white text-black fixed bottom-0 left-0 top-16 w-36 p-3 flex flex-col">
        <div class="text-2xl @if($mode == "taken" OR $mode == "spent") {{ "font-bold" }} @endif">Individuel</div>
        <a class="text-xl ml-1 mt-3 @if($mode == "taken") {{ "font-bold" }} @endif" href="taken">Appels pris</a>
        <a class="text-xl ml-1 mt-3 @if($mode == "spent") {{ "font-bold" }} @endif" href="spent">Temps passé</a><br/>
        <div class="text-2xl mt-3 @if($mode == "got" OR $mode == "lost" OR $mode == "time") {{ "font-bold" }} @endif">Groupe</div>
        <a class="text-xl ml-1 mt-3 @if($mode == "got") {{ "font-bold" }} @endif" href="got">Appels traités</a>
        <a class="text-xl ml-1 mt-3 @if($mode == "lost") {{ "font-bold" }} @endif" href="lost">Appels perdus</a>
        <a class="text-xl ml-1 mt-3 @if($mode == "time") {{ "font-bold" }} @endif" href="time">Temps réponse</a>
    </div>

    <div id="drap" class="bg-black bg-opacity-95 fixed w-full h-full top-0 left-0 transition duration-500 opacity-0 -z-1" onclick="closeSelector()"></div>
    <div id="datePopup" class="bg-white w-48 absolute top-32 left-1/2 flex pt-0 pr-3 pb-3 pl-3 flex flex-col shadow shadow-2xl rounded-lg select-none text-xl hidden">
        <div id="navDatePopup" class="w-full mt-3 mb-3 font-bold"> Entrez une plage : </div>
        <form id="dateSelectors" class="flex flex-col align-middle text-gray-400 mb-3">
            Début
            <input type="date" name="start" id="startForm" class="mb-3" value="{{ $start }}">
            Fin
            <input type="date" name="end" id="endForm" class="mb-3" value="{{ $end }}">
        </form>
        <div class="flex flex-row-reverse">
            <button class="text-epm bg-none border-none pl-4 pr-1" onclick="validSelector()"> OK </button>
            <button class="text-epm bg-none border-none" onclick="closeSelector()"> ANNULER </button>
        </div>
    </div>

</div>

</body>
<script>
    function openSelector(){
        document.getElementById('datePopup').style.display = "block";
        document.getElementById('datePopup').style.opacity = "1";
        document.getElementById('drap').style.opacity = "0.9";
        document.getElementById('drap').style.zIndex = "0";
    }


    function closeSelector() {
        document.getElementById('datePopup').style.opacity = "0";
        document.getElementById('datePopup').style.display = "none";
        document.getElementById('drap').style.opacity = "0";
        setTimeout(() => {document.getElementById('drap').style.zIndex = "-1";}, 500);
        stopDateSelectorFollowCursor()
    }

    function validSelector() {
        document.getElementById('startInHeader').value = new Date(document.getElementById('startForm').value).toLocaleDateString("fr-FR");
        document.getElementById('endInHeader').value = new Date(document.getElementById('endForm').value).toLocaleDateString("fr-FR");
        closeSelector()
    }

    var dateSelectorFollowCursor = (
        function() {
            let s = document.getElementById('datePopup');
            let check = 0;
            let xMouse = s.style.left;
            let yMouse = s.style.top;

            return {
                init: function() {
                    document.body.appendChild(s);
                    check = 1;
                },

                run: function(e) {
                    var e = e || window.event;
                    if (check == 1) {
                        xMouse = e.clientX - s.offsetLeft;
                        yMouse = e.clientY - s.offsetTop;
                        check = 0;
                    }
                    s.style.left = (e.clientX - xMouse) + "px";
                    s.style.top = (e.clientY - yMouse) + "px";
                },

                stop: function () {
                }
            };
        }());

    function initDateSelectorFollowCursor(){
        dateSelectorFollowCursor.init();
        document.body.onmousemove = dateSelectorFollowCursor.run;
    }

    function stopDateSelectorFollowCursor(){
        document.body.onmousemove = dateSelectorFollowCursor.stop;
    }

    document.getElementById("navDatePopup").onmousedown = function (){initDateSelectorFollowCursor()};
    document.getElementById("datePopup").onmouseup = function (){stopDateSelectorFollowCursor()};

</script>
</html>
