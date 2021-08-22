<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Cdr</title>
</head>
<body class="antialiased bg-epmLighter">
<?php

$classement = 0;

function cmp($a, $b)
{
    return $b['total'] <=> $a['total'];
}
?>


<form class="bg-epm text-epmLight fixed w-full top-0 h-16 flex flex-column text-3xl items-center" action="">
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
    <div class="text-2xl leftMenu" id="ind">Individuel</div>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="getIndMenu" onclick="changeMode('getInd')">Appels pris</a>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="spentIndMenu" onclick="changeMode('spentInd')">Temps passé</a>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="timeIndMenu" onclick="changeMode('timeInd')">Temps de réponse</a>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="lostIndMenu" onclick="changeMode('lostInd')">Appels perdus</a><br/>
    <div class="text-2xl leftMenu" id="grp">Groupe</div>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="getGrpMenu" onclick="changeMode('getGrp')">Appels pris</a>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="spentGrpMenu" onclick="changeMode('spentGrp')">Temps passé</a>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="timeGrpMenu" onclick="changeMode('timeGrp')">Temps de réponse</a>
    <a class="text-xl ml-1 mt-3 leftMenu cursor-pointer" id="lostGrpMenu" onclick="changeMode('lostGrp')">Appels perdus</a>
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


</body>
<script>
    function changeMode($mode){
        let all = document.getElementsByClassName('leftMenu');
        for (let i = 0; i < all.length; i++){
            all[i].style.fontWeight = "normal";
        }
        all = document.getElementsByClassName('tabs');
        for (let i = 0; i < all.length; i++){
            all[i].style.display = "none";
        }
        document.getElementById($mode + 'Menu').style.fontWeight = 'bold';
        document.getElementById($mode).style.display = 'flex';
        if($mode == 'getInd' || $mode == 'lostInd' || $mode == 'spentInd' || $mode == 'timeInd'){
            document.getElementById('ind').style.fontWeight = 'bold';
        }
        else {
            document.getElementById('grp').style.fontWeight = 'bold';
        }
    }

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
