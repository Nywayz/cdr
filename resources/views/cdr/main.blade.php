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
        table, th, td {
            border: 1px solid black;
            padding: 3px;
        }
    </style>


</head>
<body class="antialiased">

<div class="p-5 bg-black text-white flex">
    <div>
        <form action="stats">
            @method("post")
            <input class="text-black" type="date" id="start1" name="start" value="@if($infos->start != "") {{ $infos->start }}@else{{ (date('Y'))."-".date("m", strtotime("-1 month"))."-01" }}@endif">
            <input class="text-black" type="date" id="end1" name="end" value="@if($infos->end != "") {{ $infos->end }}@else{{ date("Y-m-d", strtotime("-".date('d')." days")) }}@endif"><br/><br/>
            <div class="text-xl">Postes :</div>
            <input type="checkbox" name="allChecked" @if(isset($infos->allChecked))checked @endif onclick="checkAll(this,0)"> Tous<br/>
            @foreach($users as $user)
                <input type="checkbox" class="box" name="selectedUsers[{{ $user->Number }}§{{ $user->Name }}]" id="{{ $user->Number }}§{{ $user->Name }}" @if(isset($infos->selectedUsers[$user->Number.'§'.$user->Name]))checked @endif value="{{ $user->Number }}§{{ $user->Name }}"><label for="{{ $user->Number }}§{{ $user->Name }}"> {{ $user->Number }} {{ $user->Name }}</label><br/>
            @endforeach<br/>
            <div class="text-xl">Obtenir la table :</div>
            <input type="radio" name="group" value="taken" id="taken" @if($infos->group != 'taken') checked @endif><label for="taken"> Appels pris</label><br/>
            <input type="radio" name="group" value="spent" id="spent" @if($infos->group == 'spent') checked @endif><label for="spent"> Temps en communication</label><br/><br/>
            <input class="text-black" type="submit" value="Valider">
        </form>
    </div>
    <div>
        <form action="stats" onsubmit="mirrorDate()">
            @method("post")
            <input class="text-black invisible" id="start2" type="date" name="start">
            <input class="text-black invisible" id="end2" type="date" name="end"><br/><br/>
            <div class="text-xl">Groupes :</div>
            <input type="checkbox" name="allChecked" @if(isset($infos->allChecked))checked @endif onclick="checkAll(this,1)"> Tous<br/>
            @foreach($groups as $group)
                <input type="checkbox" class="box1" name="selectedUsers[{{ $group->Tag }}]" id="{{ $group->Tag }}" @if(isset($group->Tag[$group->Tag]))checked @endif value="{{ $group->Tag }}"><label for="{{ $group->Tag }}"> {{ $group->Tag }}</label><br/>
            @endforeach<br/>
            <div class="text-xl">Obtenir la table :</div>
            <input type="radio" name="group" value="got" id="got" @if($infos->group != 'got') checked @endif><label for="got"> Nombre des appels traités</label><br/>
            <input type="radio" name="group" value="lost" id="lost" @if($infos->group == 'lost') checked @endif><label for="lost"> Appels perdus</label><br/>
            <input type="radio" name="group" value="time" id="time" @if($infos->group == 'time') checked @endif><label for="time"> Temps de réponse</label><br/><br/>
            <input class="text-black" type="submit" value="Valider" onclick="mirrorDate()">
        </form>
    </div>
</div>

</body>

<script>
    function checkAll($isTrue, $which){
        if ($which == 0){
            for(let i = 0; i < document.getElementsByClassName('box').length; i++){
                document.getElementsByClassName('box')[i].checked = $isTrue.checked;
            }
        }
        if ($which == 1){
            for(let i = 0; i < document.getElementsByClassName('box1').length; i++){
                document.getElementsByClassName('box1')[i].checked = $isTrue.checked;
            }
        }
    }

    function mirrorDate(){
        document.getElementById('start2').value = document.getElementById('start1').value;
        document.getElementById('end2').value = document.getElementById('end1').value;

        return true;
    }
</script>

</html>
