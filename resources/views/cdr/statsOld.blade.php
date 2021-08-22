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

    function cmp($a, $b)
    {
        return $b['total'] <=> $a['total'];
    }
?>

<div class="bg-epmLighter text-black mt-16 ml-36 right-0 bottom-0 pr-20 pl-20 pt-10 pb-20 flex flex-wrap justify-self-center">

    @switch($infos->group)
    @case('taken')
    <div class="w-full">
        <h2 class="font-bold">Nombre d'appels entrants pris par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-none"></th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white"></th>
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
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">19</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">H. plages</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['name'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eight']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['nine']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['ten']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eleven']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['twelve']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['thirteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['fourteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['fifteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['sixteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['seventeen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eighteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['nineteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['total'] }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" colspan="2" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['number'];} echo $total1; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['number'];} echo $total2; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['number'];} echo $total3; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['number'];} echo $total4; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['number'];} echo $total5; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['number'];} echo $total6; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['number'];} echo $total7; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total8 = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['number'];} echo $total8; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total9 = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['number'];} echo $total9; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total10 = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['number'];} echo $total10; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total11 = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['number'];} echo $total11; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total12 = 0; foreach ($users as $user){ $total12 += $user['hours']['nineteen']['number'];} echo $total12; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total13 = 0; foreach ($users as $user){ $total13 += $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'];} echo $total13; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12 +  $total13 }}</td>
        </table><br/>
    </div>
    <div class="w-full flex p-0">
        <div class="w-1/2">
        <h2 class="font-bold">Nombre d'appels entrants pris par jour entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-none"></th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white"></th>
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
                    <td class="pl-3 pr-3 bg-white">{{ $user['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['name'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['monday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['tuesday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['wednesday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['thursday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['friday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['saturday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['sunday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['total'] }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" colspan="2" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['number'];} echo $total1; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['number'];} echo $total2; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['number'];} echo $total3; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['number'];} echo $total4; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['number'];} echo $total5; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['number'];} echo $total6; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['number'];} echo $total7; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 }}</td>
        </table>
    </div>
    <div class="w-1/2">
        <?php usort($users, "cmp"); ?>

        <h2 class="font-bold">Classement de la prise d'appels entrant entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $classement = $classement + 1 }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['name'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['total'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    </div>

    @break
    @case('spent')
    <div>
        <h2 class="font-bold">Temps passé en mins au téléphone (entrants et sortants) par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-none"></th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white"></th>
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
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">19</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">H. plages</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['name'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eight']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['nine']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['ten']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eleven']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['twelve']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['thirteen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['fourteen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['fifteen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['sixteen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['seventeen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eighteen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['nineteen']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round(($user['hours']['one']['spent'] + $user['hours']['two']['spent'] + $user['hours']['three']['spent'] + $user['hours']['four']['spent'] + $user['hours']['five']['spent'] + $user['hours']['six']['spent'] + $user['hours']['seven']['spent'] + $user['hours']['twenty']['spent'] + $user['hours']['twentyOne']['spent'] + $user['hours']['twentyTwo']['spent'] + $user['hours']['twentyThree']['spent']) / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['total'] / 60, 0) }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" colspan="2" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['spent'];} echo round($total1 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['spent'];} echo round($total2 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['spent'];} echo round($total3 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['spent'];} echo round($total4 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['spent'];} echo round($total5 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['spent'];} echo round($total6 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['spent'];} echo round($total7 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total8 = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['spent'];} echo round($total8 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total9 = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['spent'];} echo round($total9 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total10 = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['spent'];} echo round($total10 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total11 = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['spent'];} echo round($total11 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total12 = 0; foreach ($users as $user){ $total12 += $user['hours']['nineteen']['spent'];} echo round($total12 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total13 = 0; foreach ($users as $user){ $total13 += $user['hours']['one']['spent'] + $user['hours']['two']['spent'] + $user['hours']['three']['spent'] + $user['hours']['four']['spent'] + $user['hours']['five']['spent'] + $user['hours']['six']['spent'] + $user['hours']['seven']['spent'] + $user['hours']['twenty']['spent'] + $user['hours']['twentyOne']['spent'] + $user['hours']['twentyTwo']['spent'] + $user['hours']['twentyThree']['spent'];} echo round($total13 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ round(($total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12 +  $total13)  / 60, 0) }}</td>
        </table><br/>
        <h2 class="font-bold">Temps passé en mins au téléphone (entrants et sortants) par jour entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-none"></th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white"></th>
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
                    <td class="pl-3 pr-3 bg-white">{{ $user['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['name'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['monday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['tuesday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['wednesday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['thursday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['friday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['saturday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['sunday']['spent'] / 60, 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['total'] / 60, 0) }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" colspan="2" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['spent'];} echo round($total1 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['spent'];} echo round($total2 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['spent'];} echo round($total3 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['spent'];} echo round($total4 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['spent'];} echo round($total5 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['spent'];} echo round($total6 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['spent'];} echo round($total7 / 60, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ round(($total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7) / 60, 0) }}</td>
        </table>

        <?php usort($users, "cmp"); ?>

        <h2 class="font-bold">Classement des temps passés en minutes au téléphone (entrants & sortants) entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $classement = $classement + 1 }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['name'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['total'] }}</td>
                </tr>
            @endforeach
        </table>
    @break
    @case('got')
        <h2 class="font-bold">Nombre d'appels entrants pris par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">19</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">H. plages</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eight']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['nine']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['ten']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eleven']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['twelve']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['thirteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['fourteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['fifteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['sixteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['seventeen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eighteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['nineteen']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['hours']['total'] }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['number'];} echo $total1; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['number'];} echo $total2; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['number'];} echo $total3; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['number'];} echo $total4; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['number'];} echo $total5; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['number'];} echo $total6; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['number'];} echo $total7; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total8 = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['number'];} echo $total8; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total9 = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['number'];} echo $total9; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total10 = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['number'];} echo $total10; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total11 = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['number'];} echo $total11; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total12 = 0; foreach ($users as $user){ $total12 += $user['hours']['nineteen']['number'];} echo $total12; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total13 = 0; foreach ($users as $user){ $total13 += $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'];} echo $total13; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12 +  $total13 }}</td>
        </table><br/>
    </div>
    <div>
        <h2 class="font-bold">Nombre d'appels entrants pris par jour entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['monday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['tuesday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['wednesday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['thursday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['friday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['saturday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['sunday']['number'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['total'] }}</td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['number'];} echo $total1; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['number'];} echo $total2; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['number'];} echo $total3; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['number'];} echo $total4; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['number'];} echo $total5; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['number'];} echo $total6; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['number'];} echo $total7; ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 }}</td>
        </table>
    </div>
    <div>
        <?php usort($users, "cmp"); ?>

        <h2 class="font-bold">Nombre d'appels entrants traités sur chaque groupe entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['total'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    @break
    @case('lost')
    <div>
    <h2 class="font-bold">Nombre d'appels entrants pris par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
            <th style="padding: 5px 10px" class="border-black border-1 bg-white">19</th>
            <th style="padding: 5px 10px" class="border-black border-1 bg-white">H. plages</th>
            <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
        </tr>
        @foreach($users as $user)
            <tr class="border-none">
                <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eight']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['nine']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['ten']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eleven']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['twelve']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['thirteen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['fourteen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['fifteen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['sixteen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['seventeen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['eighteen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['nineteen']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['hours']['total'] }}</td>
            </tr>
        @endforeach
        <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['number'];} echo $total1; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['number'];} echo $total2; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['number'];} echo $total3; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['number'];} echo $total4; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['number'];} echo $total5; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['number'];} echo $total6; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['number'];} echo $total7; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total8 = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['number'];} echo $total8; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total9 = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['number'];} echo $total9; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total10 = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['number'];} echo $total10; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total11 = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['number'];} echo $total11; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total12 = 0; foreach ($users as $user){ $total12 += $user['hours']['nineteen']['number'];} echo $total12; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total13 = 0; foreach ($users as $user){ $total13 += $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'];} echo $total13; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12 +  $total13 }}</td>
    </table><br/>
    </div>
    <div>
    <h2 class="font-bold">Nombre d'appels entrants pris par jour entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
                <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['monday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['tuesday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['wednesday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['thursday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['friday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['saturday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['sunday']['number'] }}</td>
                <td class="pl-3 pr-3 bg-white">{{ $user['weekdays']['total'] }}</td>
            </tr>
        @endforeach
        <td style="padding: 5px 10px" class="border-black border-1 bg-white">Moyenne</td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['number'];} echo $total1; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['number'];} echo $total2; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['number'];} echo $total3; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['number'];} echo $total4; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['number'];} echo $total5; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['number'];} echo $total6; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['number'];} echo $total7; ?></td>
        <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 }}</td>
    </table>
    </div>
    <div>
        <?php usort($users, "cmp"); ?>

        <h2 class="font-bold">Nombre d'appels entrants non répondu sur chaque groupe entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['total'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    @break
        @case('time')
    <div>
        <h2 class="font-bold">Temps moyen de décrochage (secondes) sur chaque groupe par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Total</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eight']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['nine']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['ten']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eleven']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['twelve']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['thirteen']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['fourteen']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['fifteen']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['sixteen']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['seventeen']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['hours']['eighteen']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white"><?php $total = 0; $number = 0; foreach ($user['hours'] as $hour){if ($hour['spent'] != 0){$total += $hour['spent']; $number += 1;}} if ($number == "0"){$number = 1;} echo round($total / $number, 0);  ?></td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">Moyenne</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['eight']['spent'] != 0){ $total1 += $user['hours']['eight']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total1 = $total1 / $number; echo round($total1, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['nine']['spent'] != 0){ $total2 += $user['hours']['nine']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total2 = $total2 / $number; echo round($total2, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['ten']['spent'] != 0){ $total3 += $user['hours']['ten']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total3 = $total3 / $number; echo round($total3, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['eleven']['spent'] != 0){ $total4 += $user['hours']['eleven']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total4 = $total4 / $number; echo round($total4, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['twelve']['spent'] != 0){ $total5 += $user['hours']['twelve']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total5 = $total5 / $number; echo round($total5, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['thirteen']['spent'] != 0){ $total6 += $user['hours']['thirteen']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total6 = $total6 / $number; echo round($total6, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['fourteen']['spent'] != 0){ $total7 += $user['hours']['fourteen']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total7 = $total7 / $number; echo round($total7, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total8 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['fifteen']['spent'] != 0){ $total8 += $user['hours']['fifteen']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total8 = $total8 / $number; echo round($total8, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total9 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['sixteen']['spent'] != 0){ $total9 += $user['hours']['sixteen']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total9 = $total9 / $number; echo round($total9, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total10 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['seventeen']['spent'] != 0){ $total10 += $user['hours']['seventeen']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total10 = $total10 / $number; echo round($total10, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total11 = 0; $number = 0; foreach ($users as $user){ if($user['hours']['eighteen']['spent'] != 0){ $total11 += $user['hours']['eighteen']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total11 = $total11 / $number; echo round($total11, 0); ?></td>
            <?php //<td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total13 = 0; $number = 0; foreach ($users as $user){ if($total1 != 0){$total13 += $total1; $number += 1;}if($total2 != 0){$total13 += $total2; $number += 1;}if($total2 != 0){$total13 += $total2; $number += 1;}if($total3 != 0){$total13 += $total3; $number += 1;}if($total4 != 0){$total13 += $total4; $number += 1;}if($total5 != 0){$total13 += $total5; $number += 1;}if($total6 != 0){$total13 += $total6; $number += 1;}if($total7 != 0){$total13 += $total7; $number += 1;}if($total8 != 0){$total13 += $total8; $number += 1;}if($total9 != 0){$total13 += $total9; $number += 1;}if($total10 != 0){$total13 += $total10; $number += 1;}if($total11 != 0){$total13 += $total11; $number += 1;}if($total12 != 0){$total13 += $total12; $number += 1;}if($total14 != 0){$total13 += $total14; $number += 1;}} if($number !=0){$total13 = $total13 / $number;}  echo round($total13, 0); ?>
        </table><br/>
    </div>
    <div>
        <h2 class="font-bold">Temps moyen de décrochage (secondes) sur chaque groupe entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Moyenne</th>
            </tr>
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['monday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['tuesday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['wednesday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['thursday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['friday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['saturday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ round($user['weekdays']['sunday']['spent'], 0) }}</td>
                    <td class="pl-3 pr-3 bg-white"><?php $total = 0; $number = 0; foreach ($user['weekdays'] as $weekday){if ($weekday['spent'] != 0){$total += $weekday['spent']; $number += 1;}} if ($number == "0"){$number = 1;} echo round($total / $number, 0);  ?></td>
                </tr>
            @endforeach
            <td style="padding: 5px 10px" class="border-black border-1 bg-white">Moyenne</td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total1 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['monday']['spent'] != 0){ $total1 += $user['weekdays']['monday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total1 = $total1 / $number; echo round($total1, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total2 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['tuesday']['spent'] != 0){ $total2 += $user['weekdays']['tuesday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total2 = $total2 / $number; echo round($total2, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total3 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['wednesday']['spent'] != 0){ $total3 += $user['weekdays']['wednesday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total3 = $total3 / $number; echo round($total3, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total4 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['thursday']['spent'] != 0){ $total4 += $user['weekdays']['thursday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total4 = $total4 / $number; echo round($total4, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total5 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['friday']['spent'] != 0){ $total5 += $user['weekdays']['friday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total5 = $total5 / $number; echo round($total5, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total6 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['saturday']['spent'] != 0){ $total6 += $user['weekdays']['saturday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total6 = $total6 / $number; echo round($total6, 0); ?></td>
            <td style="padding: 5px 10px" class="border-black border-1 bg-white"><?php $total7 = 0; $number = 0; foreach ($users as $user){ if($user['weekdays']['sunday']['spent'] != 0){ $total7 += $user['weekdays']['sunday']['spent']; $number += 1;}} if ($number == "0"){$number = 1;} $total7 = $total7 / $number; echo round($total7, 0); ?></td>
        </table>
    </div>
    <div>
        <?php usort($users, "cmp"); ?>

        <h2 class="font-bold">Temps moyen de décrochage (s) sur chaque groupe entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            @foreach($users as $user)
                <tr class="border-none">
                    <td class="pl-3 pr-3 bg-white">{{ $user['complete'] }}</td>
                    <td class="pl-3 pr-3 bg-white">{{ $user['total'] }}</td>
                </tr>
            @endforeach
        </table>
        @break
    </div>

    @endswitch
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
