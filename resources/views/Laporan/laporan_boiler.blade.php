<!-- resources/views/laporan/laporan-boiler.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Boiler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .chart-container {
            width: 100%;
            height: 300px;
            margin-bottom: 30px;
            position: relative;
        }
        .operating-hours {
            width: 100%;
            margin-bottom: 30px;
        }
        .total-hours {
            text-align: center;
            font-size: 72px;
            font-weight: bold;
        }
        .hours-label {
            text-align: center;
            font-size: 24px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #777;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="header">
        <h1>PTPN V | PKS SEI INTAN</h1>
        <div class="info-row">
            <div>Tanggal: {{ $tanggal }}</div>
            <div>ID Perangkat: {{ $idPerangkat }}</div>
        </div>
    </div>
    
    <h2>Riwayat Tekanan</h2>
    <div class="chart-container">
        <canvas id="grafikTekanan"></canvas>
    </div>
    
    <h2>Riwayat Jam Jalan</h2>
    <div class="operating-hours">
        <canvas id="grafikJamOperasi"></canvas>
    </div>
    
    <div class="total-hours">
        {{ $totalJam }}
    </div>
    <div class="hours-label">Jam</div>
    
    <div class="footer">
        Copyright © 2022. Holding Perkebunan Nusantara.
    </div>
    
    <script>
        // Chart.js script untuk riwayat tekanan
        document.addEventListener('DOMContentLoaded', function() {
            var ctxTekanan = document.getElementById('grafikTekanan').getContext('2d');
            var ctxJamOperasi = document.getElementById('grafikJamOperasi').getContext('2d');
            
            // Grafik tekanan
            var grafikTekanan = new Chart(ctxTekanan, {
                type: 'line',
                data: {
                    labels: {!! $dataGrafikTekanan['label'] !!},
                    datasets: [{
                        label: 'Tekanan (Atm)',
                        data: {!! $dataGrafikTekanan['nilai'] !!},
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 2,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        tension: 0.2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: {{ $dataGrafikTekanan['min'] - 2 }},
                            max: {{ $dataGrafikTekanan['max'] + 2 }}
                        }
                    }
                }
            });
            
            // Grafik jam operasi (bar horizontal)
            var grafikJamOperasi = new Chart(ctxJamOperasi, {
                type: 'bar',
                data: {
                    labels: ['Jam Jalan'],
                    datasets: [{
                        label: 'Jam Operasi',
                        data: [{{ $totalJam }}],
                        backgroundColor: 'rgba(33, 150, 243, 0.8)',
                        borderColor: 'rgba(33, 150, 243, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 24,
                            ticks: {
                                callback: function(value) {
                                    return value + ':00';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>