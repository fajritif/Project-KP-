<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Widget</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($widgets as $widget)
                <tr>
                    <td>{{ $widget->id }}</td>
                    <td>{{ $widget->name }}</td>
                    <td>{{ $widget->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
