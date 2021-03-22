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
    </style>


</head>
<body class="antialiased">

<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
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
            <button class="tablinks" onclick="openCity(event, 'PerfIndiA')">Perf. Individuelles (Appels)</button>
            <button class="tablinks" onclick="openCity(event, 'PerfIndiT')">Perf. Individuelles (Temps)</button>
            <button class="tablinks" onclick="openCity(event, 'PerfGrp')">Perf. Groupe</button>
        </div>

        <!-- Tab content -->
        <div id="PerfIndiA" class="tabcontent">
            <div class="text-blue-500">
                <table style="padding: 2px">
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
                            <td>{{ $user['weekdays']['monday']['number'] + $user['weekdays']['tuesday']['number'] + $user['weekdays']['wednesday']['number'] + $user['weekdays']['thursday']['number'] + $user['weekdays']['friday']['number'] + $user['weekdays']['saturday']['number'] + $user['weekdays']['sunday']['number'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div id="PerfIndiT" class="tabcontent">
            <div class="text-red-500">

                <table>
                    <th></th>
                    <th></th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>En dehors des plages</th>
                    <th>Total appels pris</th>
                    <th>Classement</th>
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
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>

</body>
</html>
