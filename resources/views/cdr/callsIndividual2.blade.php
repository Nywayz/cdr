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
        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
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


</head>
<body class="antialiased">

<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:flex">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                <a href="{{ url('/logout') }}" class="text-sm text-gray-700 underline">Log out</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif

</div>

@if (Route::has('login'))
    @auth
        <div style="height: 200px"></div>
        <div class="tab">
            <button class="tablinks active" onclick="openCity(event, 'PerfIndiA')">Perf. Individuelles (Appels)</button>
            <button class="tablinks" onclick="openCity(event, 'PerfIndiT')">Perf. Individuelles (Temps)</button>
            <button class="tablinks" onclick="openCity(event, 'PerfGrp')">Perf. Groupe</button>
        </div>

        <!-- Tab One - Performances individuelles (Appels) -->
        <div id="PerfIndiA" class="tabcontent" style="display: flex">
            <div class="text-blue-500">
                <table>
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px">L</th>
                    <th style="padding: 5px 10px">M</th>
                    <th style="padding: 5px 10px">M</th>
                    <th style="padding: 5px 10px">J</th>
                    <th style="padding: 5px 10px">V</th>
                    <th style="padding: 5px 10px">S</th>
                    <th style="padding: 5px 10px">D</th>
                    <th style="padding: 5px 10px">Total</th>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['weekdays']['monday']['number'] }}</td>
                            <td>{{ $user['weekdays']['tuesday']['number'] }}</td>
                            <td>{{ $user['weekdays']['wednesday']['number'] }}</td>
                            <td>{{ $user['weekdays']['thursday']['number'] }}</td>
                            <td>{{ $user['weekdays']['friday']['number'] }}</td>
                            <td>{{ $user['weekdays']['saturday']['number'] }}</td>
                            <td>{{ $user['weekdays']['sunday']['number'] }}</td>
                            <td>{{ $user['total'] }}</td>
                        </tr>
                    @endforeach
                    <td style="padding: 5px 10px" colspan="2">Total</td>
                    <td style="padding: 5px 10px"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['number'];} echo $total1; ?></td>
                    <td style="padding: 5px 10px"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['number'];} echo $total2; ?></td>
                    <td style="padding: 5px 10px"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['number'];} echo $total3; ?></td>
                    <td style="padding: 5px 10px"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['number'];} echo $total4; ?></td>
                    <td style="padding: 5px 10px"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['number'];} echo $total5; ?></td>
                    <td style="padding: 5px 10px"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['number'];} echo $total6; ?></td>
                    <td style="padding: 5px 10px"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['number'];} echo $total7; ?></td>
                    <td style="padding: 5px 10px">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 }}</td>
                </table>
            </div>
            <div style="margin: 10px"></div>
            <div class="text-red-500">
                <table style="padding: 3px">
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px">8</th>
                    <th style="padding: 5px 10px">9</th>
                    <th style="padding: 5px 10px">10</th>
                    <th style="padding: 5px 10px">11</th>
                    <th style="padding: 5px 10px">12</th>
                    <th style="padding: 5px 10px">13</th>
                    <th style="padding: 5px 10px">14</th>
                    <th style="padding: 5px 10px">15</th>
                    <th style="padding: 5px 10px">16</th>
                    <th style="padding: 5px 10px">17</th>
                    <th style="padding: 5px 10px">18</th>
                    <th style="padding: 5px 10px">19</th>
                    <th style="padding: 5px 10px">H. plages</th>
                    <th style="padding: 5px 10px">Total</th>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['hours']['eight']['number'] }}</td>
                            <td>{{ $user['hours']['nine']['number'] }}</td>
                            <td>{{ $user['hours']['ten']['number'] }}</td>
                            <td>{{ $user['hours']['eleven']['number'] }}</td>
                            <td>{{ $user['hours']['twelve']['number'] }}</td>
                            <td>{{ $user['hours']['thirteen']['number'] }}</td>
                            <td>{{ $user['hours']['fourteen']['number'] }}</td>
                            <td>{{ $user['hours']['fifteen']['number'] }}</td>
                            <td>{{ $user['hours']['sixteen']['number'] }}</td>
                            <td>{{ $user['hours']['seventeen']['number'] }}</td>
                            <td>{{ $user['hours']['eighteen']['number'] }}</td>
                            <td>{{ $user['hours']['nineteen']['number'] }}</td>
                            <td>{{ $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'] }}</td>
                            <td>{{ $user['total'] }}</td>
                        </tr>
                    @endforeach
                    <td style="padding: 5px 10px" colspan="2">Total</td>
                    <td style="padding: 5px 10px"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['number'];} echo $total1; ?></td>
                    <td style="padding: 5px 10px"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['number'];} echo $total2; ?></td>
                    <td style="padding: 5px 10px"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['number'];} echo $total3; ?></td>
                    <td style="padding: 5px 10px"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['number'];} echo $total4; ?></td>
                    <td style="padding: 5px 10px"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['number'];} echo $total5; ?></td>
                    <td style="padding: 5px 10px"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['number'];} echo $total6; ?></td>
                    <td style="padding: 5px 10px"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['number'];} echo $total7; ?></td>
                    <td style="padding: 5px 10px"><?php $total8 = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['number'];} echo $total8; ?></td>
                    <td style="padding: 5px 10px"><?php $total9 = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['number'];} echo $total9; ?></td>
                    <td style="padding: 5px 10px"><?php $total10 = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['number'];} echo $total10; ?></td>
                    <td style="padding: 5px 10px"><?php $total11 = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['number'];} echo $total11; ?></td>
                    <td style="padding: 5px 10px"><?php $total12 = 0; foreach ($users as $user){ $total12 += $user['hours']['nineteen']['number'];} echo $total12; ?></td>
                    <td style="padding: 5px 10px"><?php $total13 = 0; foreach ($users as $user){ $total13 += $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'];} echo $total13; ?></td>
                    <td style="padding: 5px 10px">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12 +  $total13 }}</td>
                </table>

            </div>
        </div>

        <!-- Tab One - Performances individuelles (Temps) -->
        <div id="PerfIndiT" class="tabcontent">
            <div class="text-blue-500">
                <table>
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px">L</th>
                    <th style="padding: 5px 10px">M</th>
                    <th style="padding: 5px 10px">M</th>
                    <th style="padding: 5px 10px">J</th>
                    <th style="padding: 5px 10px">V</th>
                    <th style="padding: 5px 10px">S</th>
                    <th style="padding: 5px 10px">D</th>
                    <th style="padding: 5px 10px">Total</th>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['weekdays']['monday']['number'] }}</td>
                            <td>{{ $user['weekdays']['tuesday']['number'] }}</td>
                            <td>{{ $user['weekdays']['wednesday']['number'] }}</td>
                            <td>{{ $user['weekdays']['thursday']['number'] }}</td>
                            <td>{{ $user['weekdays']['friday']['number'] }}</td>
                            <td>{{ $user['weekdays']['saturday']['number'] }}</td>
                            <td>{{ $user['weekdays']['sunday']['number'] }}</td>
                            <td>{{ $user['total'] }}</td>
                        </tr>
                    @endforeach
                    <td style="padding: 5px 10px" colspan="2">Total</td>
                    <td style="padding: 5px 10px"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['weekdays']['monday']['number'];} echo $total1; ?></td>
                    <td style="padding: 5px 10px"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['weekdays']['tuesday']['number'];} echo $total2; ?></td>
                    <td style="padding: 5px 10px"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['weekdays']['wednesday']['number'];} echo $total3; ?></td>
                    <td style="padding: 5px 10px"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['weekdays']['thursday']['number'];} echo $total4; ?></td>
                    <td style="padding: 5px 10px"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['weekdays']['friday']['number'];} echo $total5; ?></td>
                    <td style="padding: 5px 10px"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['weekdays']['saturday']['number'];} echo $total6; ?></td>
                    <td style="padding: 5px 10px"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['weekdays']['sunday']['number'];} echo $total7; ?></td>
                    <td style="padding: 5px 10px">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 }}</td>
                </table>
            </div>
            <div style="margin: 10px"></div>
            <div class="text-red-500">
                <table style="padding: 3px">
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px"></th>
                    <th style="padding: 5px 10px">8</th>
                    <th style="padding: 5px 10px">9</th>
                    <th style="padding: 5px 10px">10</th>
                    <th style="padding: 5px 10px">11</th>
                    <th style="padding: 5px 10px">12</th>
                    <th style="padding: 5px 10px">13</th>
                    <th style="padding: 5px 10px">14</th>
                    <th style="padding: 5px 10px">15</th>
                    <th style="padding: 5px 10px">16</th>
                    <th style="padding: 5px 10px">17</th>
                    <th style="padding: 5px 10px">18</th>
                    <th style="padding: 5px 10px">19</th>
                    <th style="padding: 5px 10px">H. plages</th>
                    <th style="padding: 5px 10px">Total</th>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['hours']['eight']['number'] }}</td>
                            <td>{{ $user['hours']['nine']['number'] }}</td>
                            <td>{{ $user['hours']['ten']['number'] }}</td>
                            <td>{{ $user['hours']['eleven']['number'] }}</td>
                            <td>{{ $user['hours']['twelve']['number'] }}</td>
                            <td>{{ $user['hours']['thirteen']['number'] }}</td>
                            <td>{{ $user['hours']['fourteen']['number'] }}</td>
                            <td>{{ $user['hours']['fifteen']['number'] }}</td>
                            <td>{{ $user['hours']['sixteen']['number'] }}</td>
                            <td>{{ $user['hours']['seventeen']['number'] }}</td>
                            <td>{{ $user['hours']['eighteen']['number'] }}</td>
                            <td>{{ $user['hours']['nineteen']['number'] }}</td>
                            <td>{{ $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'] }}</td>
                            <td>{{ $user['total'] }}</td>
                        </tr>
                    @endforeach
                    <td style="padding: 5px 10px" colspan="2">Total</td>
                    <td style="padding: 5px 10px"><?php $total1 = 0; foreach ($users as $user){ $total1 += $user['hours']['eight']['number'];} echo $total1; ?></td>
                    <td style="padding: 5px 10px"><?php $total2 = 0; foreach ($users as $user){ $total2 += $user['hours']['nine']['number'];} echo $total2; ?></td>
                    <td style="padding: 5px 10px"><?php $total3 = 0; foreach ($users as $user){ $total3 += $user['hours']['ten']['number'];} echo $total3; ?></td>
                    <td style="padding: 5px 10px"><?php $total4 = 0; foreach ($users as $user){ $total4 += $user['hours']['eleven']['number'];} echo $total4; ?></td>
                    <td style="padding: 5px 10px"><?php $total5 = 0; foreach ($users as $user){ $total5 += $user['hours']['twelve']['number'];} echo $total5; ?></td>
                    <td style="padding: 5px 10px"><?php $total6 = 0; foreach ($users as $user){ $total6 += $user['hours']['thirteen']['number'];} echo $total6; ?></td>
                    <td style="padding: 5px 10px"><?php $total7 = 0; foreach ($users as $user){ $total7 += $user['hours']['fourteen']['number'];} echo $total7; ?></td>
                    <td style="padding: 5px 10px"><?php $total8 = 0; foreach ($users as $user){ $total8 += $user['hours']['fifteen']['number'];} echo $total8; ?></td>
                    <td style="padding: 5px 10px"><?php $total9 = 0; foreach ($users as $user){ $total9 += $user['hours']['sixteen']['number'];} echo $total9; ?></td>
                    <td style="padding: 5px 10px"><?php $total10 = 0; foreach ($users as $user){ $total10 += $user['hours']['seventeen']['number'];} echo $total10; ?></td>
                    <td style="padding: 5px 10px"><?php $total11 = 0; foreach ($users as $user){ $total11 += $user['hours']['eighteen']['number'];} echo $total11; ?></td>
                    <td style="padding: 5px 10px"><?php $total12 = 0; foreach ($users as $user){ $total12 += $user['hours']['nineteen']['number'];} echo $total12; ?></td>
                    <td style="padding: 5px 10px"><?php $total13 = 0; foreach ($users as $user){ $total13 += $user['hours']['one']['number'] + $user['hours']['two']['number'] + $user['hours']['three']['number'] + $user['hours']['four']['number'] + $user['hours']['five']['number'] + $user['hours']['six']['number'] + $user['hours']['seven']['number'] + $user['hours']['twenty']['number'] + $user['hours']['twentyOne']['number'] + $user['hours']['twentyTwo']['number'] + $user['hours']['twentyThree']['number'];} echo $total13; ?></td>
                    <td style="padding: 5px 10px">{{ $total1 + $total2 + $total3 + $total4 + $total5 + $total6 +  $total7 +  $total8 +  $total9 +  $total10 +  $total11 +  $total12 +  $total13 }}</td>
                </table>

            </div>
        </div>

        <div id="PerfGrp" class="tabcontent">
            <div class="text-gray-500">
            </div>
        </div>


    @endauth
@endif

<script>
    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "flex";
        evt.currentTarget.className += " active";
    }
</script>

</body>
</html>
