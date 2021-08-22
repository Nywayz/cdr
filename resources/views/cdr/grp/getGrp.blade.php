<?php

use JetBrains\PhpStorm\Pure;$monday = 0;
$tuesday = 0;
$wednesday = 0;
$thursday = 0;
$friday = 0;
$saturday = 0;
$sunday = 0;
$week = 0;

$eight = 0;
$nine = 0;
$ten = 0;
$eleven = 0;
$twelve = 0;
$thirteen = 0;
$fourteen = 0;
$fifteen = 0;
$sixteen = 0;
$seventeen = 0;
$eighteen = 0;
$day = 0;

$outOfBounds = 0;
$groupsToSort = array();


?>

<div class="bg-epmLighter text-black mt-16 ml-36 flex flex-wrap justify-self-center">


    <div class="w-full">
        <h2 class="font-bold">
            Nombre d'appels pris  par tranche horaire entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}
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
            @foreach($groups as $group)
                <?php $day = 0; $dayTime = 0; ?>
                <tr class="border-none">
                    <td class="border-black border-1 bg-white">{{ $group->Number }}</td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 8); $eight += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number; ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 9); $nine += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 10); $ten += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 11); $eleven += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 12); $twelve += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 13); $thirteen += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 14); $fourteen += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 15); $fifteen += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 16); $sixteen += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 17); $seventeen += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 18); $eighteen += $here->number; $day += $here->number; $dayTime += $here->time; echo $here->number;  ?></td>
                    <td class="pl-3 pr-3 bg-white"><?php $here = 0; for ($i = 0; $i < 7; $i++) $here += hasContext($group,$table, $i)->number; for ($i = 19; $i < 23; $i++) $here += hasContext($group,$table, $i)->number; $outOfBounds += $here; $day += $here; echo $here;  ?></td>
                    <td class="pl-3 pr-3 bg-white"> {{ $day }}</td>
                </tr>
                <?php array_push($groupsToSort, ["Number" => $group->Number, "Time" => $dayTime, "Got" => $day]); ?>
            @endforeach
            <tr>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $eight }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $nine }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $ten }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $eleven }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $twelve }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $thirteen }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $fourteen }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $fifteen }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $sixteen }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $seventeen }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $eighteen }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $outOfBounds }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $eight + $nine + $ten + $eleven + $twelve + $thirteen + $fourteen + $fifteen + $sixteen + $seventeen + $eighteen  }}</td>
            </tr>
        </table>
    </div>

    <div class=" flex p-0">
        <div class="w-2/3">
            <h2 class="font-bold">Nombre d'appels pris par jour entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
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
                @foreach($groups as $group)
                    <?php $week = 0 ?>
                    <tr class="border-none">
                        <td class="border-black border-1 bg-white">{{ $group->Number }}</td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Monday'); $monday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Tuesday'); $tuesday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Wednesday'); $wednesday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Thursday'); $thursday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Friday'); $friday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Saturday'); $saturday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white"><?php $here = hasContext($group,$table, 'Sunday'); $sunday += $here->number; $week += $here->number; echo $here->number; ?></td>
                        <td class="pl-3 pr-3 bg-white">{{ $week }}</td>
                    </tr>
                @endforeach
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">Total</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $monday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $tuesday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $wednesday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $thursday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $friday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $saturday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $sunday }}</td>
                <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $grandTotal = $monday + $thursday + $wednesday + $tuesday + $friday + $saturday + $sunday }}</td>
            </table>
        </div>
    </div>

    <div class="w-1/3 text-center">
        <h2 class="font-bold"> Classement des appels pris entre le {{ date("d/m/Y", strtotime($start)) }} et le {{ date("d/m/Y", strtotime($end)) }}</h2>
        <table class="p-2">
            <tr class="border-none">
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Classement</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Nom</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Nb appels pris</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Nb appels entrants</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Temps passé</th>
                <th style="padding: 5px 10px" class="border-black border-1 bg-white">Temps / appel</th>
            </tr>
            <?php $i = 0; $lastGroupNumber = 0; $groupsToSort = rank($groupsToSort);?>
            @foreach($groupsToSort as $group)
                @php $lastGroupNumber == $group["Got"]; @endphp
                <tr class="border-none">
                    <td style="padding: 5px 10px" class="border-black border-1 bg-white">@if($lastGroupNumber == $group["Got"] && $i != 0) {{ $i }} @else {{ $i += 1 }} @endif</td>
                    <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $group["Number"] }}</td>
                    <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ $group["Got"] }}</td>
                    <td style="padding: 5px 10px" class="border-black border-1 bg-white">@if($grandTotal){{ round(($group["Got"] * 100) / $grandTotal, 0) }}@else 0 @endif %</td>
                    <td style="padding: 5px 10px" class="border-black border-1 bg-white">{{ gmdate("i:s", round($group["Time"], 0)) }}</td>
                    <td style="padding: 5px 10px" class="border-black border-1 bg-white">@if($group["Got"]){{ gmdate("i:s", round(($group["Time"] / $group["Got"]), 0)) }}@else 0 @endif</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
