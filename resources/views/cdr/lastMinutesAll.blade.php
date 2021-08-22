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

        /* Tooltip container */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        /* Tooltip text */
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;

            /* Position the tooltip text - see examples below! */
            position: absolute;
            z-index: 1;
            top: 100%;
            left: 50%;
            margin-left: -60px;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
    </style>


</head>
<body class="antialiased">

    <div class="p-5 text-sm">
        Base : <select id="database"><option value="wildix02">wildix02</option><option value="wildix901" @if($infos['database'] == 'wilidx901') selected @endif>wildix901</option></select>
        Temps (minutes) : <input type="text" id="min" value="{{ $infos['min'] }}">
        Tag : <input type="text" id="filter" value="{{ $infos['filter'] }}">
        <button class="text-lg font-bold" onclick="refine()">Valider</button><br/><br/>
        <a class="text-blue-300" href="https://confluence.wildix.com/display/DOC/CDR+database+structure">Source des infos sur la structure</a>
    </div>

    <div style="display: flex" class="p-5">
        <table style="border-collapse: collapse">
            <tr>
                <th><div class="tooltip p-3">rowid<span class="tooltiptext p-3">Unique ID for CDR record</span></div></th>
                <th><div class="tooltip">src<span class="tooltiptext">The calling party’s caller ID number.</span></div></th>
                <th><div class="tooltip">dst<span class="tooltiptext">The destination extension for the call.</span></div></th>
                <th><div class="tooltip">dcontext<span class="tooltiptext">The destination context for the call (e.g. users)</span></div></th>
                <th><div class="tooltip">clid<span class="tooltiptext">Full caller ID including the name of the calling party. (e.g. "User 10001" <10001>)</span></div></th>
                <th><div class="tooltip">dstchhannel<span class="tooltiptext">The called party’s channel. (e.g. SIP/classound-00000001)</span></div></th>
                <th><div class="tooltip">lastapp<span class="tooltiptext">The last dialplan application that was executed (e.g. Dial)</span></div></th>
                <th><div class="tooltip">lastdata<span class="tooltiptext">The arguments passed to the lastapp</span></div></th>
                <th><div class="tooltip">start<span class="tooltiptext">The start time of the call (e.g. 2019-04-03 18:16:24)</span></div></th>
                <th><div class="tooltip">answer<span class="tooltiptext">The answered time of the call. (e.g. 2019-04-03 18:16:24)</span></div></th>
                <th><div class="tooltip">end<span class="tooltiptext">The end time of the call. (e.g. 2019-04-03 18:16:27)</span></div></th>
                <th><div class="tooltip">duration<span class="tooltiptext">The number of seconds between the start and end times for the call.</span></div></th>
                <th><div class="tooltip">billsec<span class="tooltiptext">The number of seconds between the answer and end times for the call.</span></div></th>
                <th><div class="tooltip">disposition<span class="tooltiptext">An indication of what happened to the call</span></div></th>
                <th><div class="tooltip">amaflags<span class="tooltiptext">The Automatic Message Accounting (AMA) flag associated with this call. This may be one of the following: OMIT, BILLING, DOCUMENTATION, or Unknown.</span></div></th>
                <th><div class="tooltip">userfield<span class="tooltiptext">A general-purpose user field. This field is empty by default and can be set to a user-defined string (Ex.: name or records)</span></div></th>
                <th><div class="tooltip">uniqueid<span class="tooltiptext">The unique ID for the src channel</span></div></th>
                <th><div class="tooltip">c_from<span class="tooltiptext">Caller number</span></div></th>
                <th><div class="tooltip">c_to<span class="tooltiptext">Callee number</span></div></th>
                <th><div class="tooltip">callclass<span class="tooltiptext">Call class</span></div></th>
                <th><div class="tooltip">pbxid<span class="tooltiptext">PBX serial</span></div></th>
                <th><div class="tooltip">srcgrp<span class="tooltiptext">Caller group id</span></div></th>
                <th><div class="tooltip">dstcrp<span class="tooltiptext">Callee group id</span></div></th>
                <th><div class="tooltip">dest_type<span class="tooltiptext">Destination type</span></div></th>
                <th><div class="tooltip">isupcause<span class="tooltiptext">ISUP cause</span></div></th>
                <th><div class="tooltip">src_rfactor<span class="tooltiptext">This field is not in use</span></div></th>
                <th><div class="tooltip">dst_rfactor<span class="tooltiptext">This field is not in use</span></div></th>
                <th><div class="tooltip">to_name<span class="tooltiptext">Callee name</span></div></th>
                <th><div class="tooltip">from_name<span class="tooltiptext">Caller name</span></div></th>
                <th><div class="tooltip">organization<span class="tooltiptext">Name of organization (if exists)</span></div></th>
                <th><div class="tooltip">email<span class="tooltiptext">Email (if exists)</span></div></th>
            </tr>
            @foreach($cdrs as $cdr)
                <tr>
                    <td>{{ $cdr->rowid }}</td>
                    <td>{{ $cdr->src }}</td>
                    <td>{{ $cdr->dst }}</td>
                    <td>{{ $cdr->dcontext }}</td>
                    <td>{{ $cdr->clid }}</td>
                    <td>{{ $cdr->dstchannel }}</td>
                    <td>{{ $cdr->lastapp }}</td>
                    <td>{{ $cdr->lastdata }}</td>
                    <td>{{ $cdr->start }}</td>
                    <td>{{ $cdr->answer }}</td>
                    <td>{{ $cdr->end }}</td>
                    <td>{{ $cdr->duration }}</td>
                    <td>{{ $cdr->billsec }}</td>
                    <td>{{ $cdr->disposition }}</td>
                    <td>{{ $cdr->amaflags }}</td>
                    <td>{{ $cdr->userfield }}</td>
                    <td>{{ $cdr->uniqueid }}</td>
                    <td>{{ $cdr->c_from }}</td>
                    <td>{{ $cdr->c_to }}</td>
                    <td>{{ $cdr->callclass }}</td>
                    <td>{{ $cdr->pbxid }}</td>
                    <td>{{ $cdr->srcgrp }}</td>
                    <td>{{ $cdr->dstgrp }}</td>
                    <td>{{ $cdr->dest_type }}</td>
                    <td>{{ $cdr->isupcause }}</td>
                    <td>{{ $cdr->src_rfactor }}</td>
                    <td>{{ $cdr->dst_rfactor }}</td>
                    <td>{{ $cdr->to_name }}</td>
                    <td>{{ $cdr->from_name }}</td>
                    <td>{{ $cdr->organization }}</td>
                    <td>{{ $cdr->email }}</td>
                </tr>
            @endforeach
        </table>
    </div>

</body>
</html>

<script>
    function refine(){
        window.location.href = "https://outil.asap-cloud.fr/cdr/cdr/lastMinutes/"+document.getElementById('database').value+"/all/"+document.getElementById('min').value+"/"+document.getElementById('filter').value;
    }

    document.addEventListener('keydown', function (event){if(event.key == "Enter"){ refine()}})
</script>
