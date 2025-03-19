<canvas id="tekananChart"></canvas>

<button id="downloadPdf">Cetak PDF</button>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('tekananChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00'],
            datasets: [{
                label: 'Tekanan',
                data: [19.38, 17.22, 21.53, 19.41, 18.20, 19.00],
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true
        }
    });

    document.getElementById('downloadPdf').addEventListener('click', function() {
        var canvas = document.getElementById('tekananChart');
        var imgData = canvas.toDataURL('image/png');

        // Simpan gambar ke storage Laravel
        fetch('/api/save-chart', {
            method: 'POST',
            body: JSON.stringify({ image: imgData }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            window.location.href = '/cetak-pdf';
        });
    });
</script>
