<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Cdr</title>
</head>
<?php

function isContextRight($context, $value): bool{
    $value = strtotime($value);
    if (is_numeric($context)){
        if (date('H', $value) == $context) return 1;
    }
    else {
        if (date('l', $value) == $context) return 1;
    }
    return 0;
}

function hasContext($data, $table, $context): object{
    $total = (object) array('number' => 0, 'time' => 0);

    if (!empty($data->Name)){
        foreach ($table as $row){
            if (preg_match('/.*main.*/i', $row->dcontext) && $row->c_to == $data->Number && $row->disposition == 'ANSWERED' && isContextRight($context, $row->start)){
                $total->number += 1;
                $total->time += $row->billsec;
            }
        }
    }
    else {
        foreach ($table as $row){
            if (preg_match('/.*main.*/i', $row->dcontext) && $row->dst == $data->Number && $row->disposition == 'ANSWERED' && isContextRight($context, $row->start)){
                $total->number += 1;
                $total->time += $row->billsec;
            }
        }
    }

    return $total;
}

function rank($data): array{
    $Number = array_column($data, 'Number');
    $Time = array_column($data, 'Time');
    $Got = array_column($data, 'Got');
    array_multisort($Got, SORT_DESC, $Time, SORT_DESC, $Number, SORT_DESC, $data);
    return $data;
}?>
@include('layouts.header')

<body>
    <div class="bg-epmLighter text-black bottom-0 ml-36 mt-16 p-3 flex flex-col ">
        <div id="getInd" class="tabs">
            @include('cdr.ind.getInd')
        </div>
        <div id="lostInd" class="hidden tabs">
            @include('cdr.ind.lostInd')
        </div>
        <div id="spentInd" class="hidden tabs">
            @include('cdr.ind.spentInd')
        </div>
        <div id="timeInd" class="hidden tabs">
            @include('cdr.ind.timeInd')
        </div>
        <div id="getGrp" class="hidden tabs">
            @include('cdr.grp.getGrp')
        </div>
        <div id="lostGrp" class="hidden tabs">
            @include('cdr.grp.lostGrp')
        </div>
        <div id="spentGrp" class="hidden tabs">
            @include('cdr.grp.spentGrp')
        </div>
        <div id="timeGrp" class="hidden tabs">
            @include('cdr.grp.timeGrp')
        </div>
    </div>
</body>

