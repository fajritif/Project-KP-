<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .chart-container {
            text-align: center;
            margin-bottom: 20px;
        }
        img {
            width: 400px;
            height: 200px;
        }
    </style>
</head>
<body>

    <h1>Laporan Data Device</h1>

    @foreach ($data as $item)
        <h3>{{ $item->TITLE_PAGE }}</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Value</th>
                <th>Unit</th>
            </tr>
            <tr>
                <td>{{ $item->DEVICE_ID }}</td>
                <td>{{ $item->PRESSURE ?? $item->TEMPERATURE ?? $item->PH ?? $item->ARUS ?? 0 }}</td>
                <td>{{ $item->SATUAN }}</td>
            </tr>
        </table>

        @php
            $chart = collect($charts)->firstWhere('id', $item->DEVICE_ID);
        @endphp
        @if ($chart && $chart['image'])
            <div class="chart-container">
                <img src="data:image/png;base64,{{ $chart['image'] }}" alt="Chart">
            </div>
        @endif
    @endforeach

</body>
</html>
