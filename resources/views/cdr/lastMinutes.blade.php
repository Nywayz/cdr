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

    <div style="display: flex">
        <table style="border-collapse: collapse">
            <tr>
                <th>rowid</th>
                <th>src</th>
                <th>dst</th>
                <th>dcontext</th>
                <th>clid</th>
                <th>channel</th>
                <th>dstchhannel</th>
                <th>lastapp</th>
                <th>lastdata</th>
                <th>start</th>
                <th>end</th>
                <th>duration</th>
                <th>billsec</th>
                <th>uniqueid</th>
                <th>c_from</th>
                <th>c_to</th>
                <th>to_name</th>
                <th>from_name</th>
                <th>pbxid</th>
            </tr>
            @foreach($cdrs as $cdr)
                <tr>
                    <td>{{ $cdr->rowid }}</td>
                    <td>{{ $cdr->src }}</td>
                    <td>{{ $cdr->dst }}</td>
                    <td>{{ $cdr->dcontext }}</td>
                    <td>{{ $cdr->clid }}</td>
                    <td>{{ $cdr->channel }}</td>
                    <td>{{ $cdr->dstchannel }}</td>
                    <td>{{ $cdr->lastapp }}</td>
                    <td>{{ $cdr->lastdata }}</td>
                    <td>{{ $cdr->start }}</td>
                    <td>{{ $cdr->end }}</td>
                    <td>{{ $cdr->duration }}</td>
                    <td>{{ $cdr->billsec }}</td>
                    <td>{{ $cdr->uniqueid }}</td>
                    <td>{{ $cdr->c_from }}</td>
                    <td>{{ $cdr->c_to }}</td>
                    <td>{{ $cdr->to_name }}</td>
                    <td>{{ $cdr->from_name }}</td>
                    <td>{{ $cdr->pbxid }}</td>
                </tr>
            @endforeach
        </table>
    </div>

</body>
</html>
