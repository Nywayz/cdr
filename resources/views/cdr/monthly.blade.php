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
<button onclick="exportTableToCSV('members.csv')">Export HTML Table To CSV File</button>

<div style="display: flex">
    <table style="border-collapse: collapse" id="tableau">
        <tr>
            <th>Date</th>
            <th>De</th>
            <th>Groupe</th>
            <th>A</th>
            <th>Groupe</th>
            <th>Pays</th>
            <th>Classe d'appel</th>
            <th>Coût</th>
            <th>Direction</th>
            <th>Statut</th>
            <th>Temps de conv</th>
            <th>Durée de la sonnerie</th>
            <th>PBX</th>
            <th>Trunk</th>
            <th>Tag</th>
            <th>Service</th>
        </tr>
        @foreach($datas as $data)
            <tr>
                <td>{{ date("d/m/Y H:i", strtotime($data->Start)) }}</td>
                <td>{{ $data->From }}</td>
                <td></td>
                <td>{{ $data->To }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @switch($data->Direction)
                    @case(2)
                    <td>Entr.</td>
                    @break
                    @case(3)
                    <td>Sort.</td>
                    @break
                    @case(4)
                    <td>Locales</td>
                    @break
                    @default
                    <td></td>
                    @break
                @endswitch
                @if($data->Answered == "ANSWERED")
                    <td>Rép.</td>
                @else
                    <td>Non rép.</td>
                @endif
                <td>{{ $data->Duration }}</td>
                <td>{{ $data->Ringing - $data->Duration }}</td>
                <td></td>
                <td></td>
                <td>{{ substr($data->Tag, 5) }}</td>
                <td></td>
            </tr>
        @endforeach
    </table>
</div>

</body>

<script>
    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {type: "text/csv"});

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }

    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("table tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
    }
</script>
</html>
