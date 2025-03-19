<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perangkat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .container {
            width: 100%;
            text-align: center;
        }
        .gauge-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .device-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Monitoring Perangkat - {{ now()->format('d-m-Y') }}</h2>
    <h4 style="text-align: center;">PKS: {{ $pks }}</h4>

    @foreach($data as $item)
        <div class="device-card">
            <h3>{{ $item->DEVICE_NAME }}</h3>
            <p>Tekanan: {{ $item->pressure }} {{ $item->unit }}</p>
            <p>Last Update: {{ $item->updated_at }}</p>
        </div>
         @endforeach
</body>
</html>
