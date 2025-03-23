@extends('layouts.app')

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
    <style>
        .cctv-btn { position: absolute; border-bottom-right-radius: 20px; }
        .cctv-btn img { height: 50px; }
        #link-stream { width: 100%; height: 100%; }
    </style>
@endpush

@push('page_scripts_header')
    <link rel="stylesheet" href="{{ url('') }}/css/video-js.css">
    <script src="{{ url('') }}/assets/js/moment.js"></script>
    <script src="{{ url('') }}/js/video.min.js"></script>
    <script src="{{ url('') }}/js/videojs-http-streaming.min.js"></script>
@endpush

@section('content')
    <h6 class="mb-0 text-uppercase">
        Data Widgets
        @foreach($pksName as $Name)
            {{ $Name->NAMA }}
        @endforeach
    </h6>
    <br>

    <!-- Tombol Cetak PDF -> memunculkan Modal -->
    <div class="d-flex align-items-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rekapModal">
            <i class="bi bi-printer"></i> Cetak PDF
        </button>
    </div>

    <!-- (Contoh) Tampilan Card Device -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4" id="gaugeContainer">
        <!-- Loop data device -->
        @foreach($data as $item)
            <div class="col">
                <div class="card radius-10" style="cursor: pointer">
                    <div onclick="handleClickDevice('{{ $item->DEVICE_ID }}')" class="card-body mt-5">
                        <div class="text-center">
                            <h6 class="mb-0 mt-3">{{ $item->DEVICE_NAME }}</h6>
                        </div>
                        <div id="{{ $item->DEVICE_ID }}"></div>
                        <div class="text-center">
                            <span id="{{ 'lastUpdate'.str_replace('-', '', $item->DEVICE_ID) }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal "Rekap Data" -->
    <div class="modal fade" id="rekapModal" tabindex="-1" aria-labelledby="rekapModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <!-- Header Modal -->
          <div class="modal-header">
            <h5 class="modal-title" id="rekapModalLabel">Rekap Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <!-- Body Modal -->
          <div class="modal-body">
            <form id="rekapForm">
              <!-- Pilih Tanggal -->
              <div class="mb-3">
                <label for="rekapDate" class="form-label">Pilih Tanggal</label>
                <input type="date" class="form-control" id="rekapDate" placeholder="dd/mm/yyyy">
              </div>
            </form>
          </div>
          <!-- Footer Modal -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-success" id="cetakPdfBtn">cetak Pdf</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('page_scripts')
  <!-- (Opsional) Datepicker JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- Highcharts -->
  <script src="{{ url('') }}/assets/plugins/highcharts/js/highcharts.js"></script>
  <script src="{{ url('') }}/assets/plugins/highcharts/js/highcharts-more.js"></script>
  <script>
      // 1) CETAK PDF -> fetch blob
      document.getElementById('cetakPdfBtn').addEventListener('click', function() {
    const dateValue = document.getElementById('rekapDate').value;

    if (!dateValue) {
        alert('Silakan pilih tanggal!');
        return;
    }

    // Ambil semua PKS yang ada di halaman (dari $pksName)
    const pksNames = @json(array_column($pksName, 'NAMA')); // Menggunakan array_column

    // Kirim tanggal dan PKS ke server
    const url = `/cobaPdf?date=${encodeURIComponent(dateValue)}&pks=${encodeURIComponent(pksNames.join(','))}`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/pdf'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
        }
        return response.blob();
    })
    .then(blob => {
        if (blob.size < 1024) {
            alert('File PDF yang diunduh sangat kecil, kemungkinan terjadi error di server.');
            return;
        }
        
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = `cobaPdf_${dateValue}.pdf`; // Nama file sesuai dengan nama endpoint
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Tutup modal setelah sukses
        $('#rekapModal').modal('hide');
    })
    .catch(error => {
        console.error('Gagal download PDF:', error);
        alert('Terjadi kesalahan saat mengunduh PDF.');
    });
});


      // 2) GAUGE & REALTIME (Contoh ringkas)
      function handleClickDevice(deviceId) {
          window.location.href = '{{ url("ptpn/device/") }}/' + deviceId;
      }

      function drawGaugeChart(chartId, lastUpdateId, dataValue, lastUpdate, gaugeTitle, gaugeSatuan, standartBlock) {
          // Tampilkan last update
          if (lastUpdate) {
              document.getElementById(lastUpdateId).textContent = "Last update " + moment(lastUpdate, "YYYY-MM-DD HH:mm:ss").fromNow();
          } else {
              document.getElementById(lastUpdateId).textContent = "Device Not Active";
          }
          return Highcharts.chart(chartId, {
              chart: { type: 'gauge', height: 200, marginTop: 0 },
              credits: { enabled: false },
              title: { text: null },
              pane: {
                  startAngle: -150, endAngle: 150,
                  background: [/* background config */]
              },
              yAxis: {
                  min: 0, max: standartBlock[2],
                  plotBands: [
                      { from: 0, to: standartBlock[0], color: '#DF5353' },
                      { from: standartBlock[0], to: standartBlock[1], color: '#DDDF0D' },
                      { from: standartBlock[1], to: standartBlock[2], color: '#55BF3B' }
                  ]
              },
              series: [{
                  name: gaugeTitle,
                  data: [dataValue],
                  tooltip: { valueSuffix: gaugeSatuan }
              }]
          });
      }

      let arrGauge = [];
      $(document).ready(function () {
          // Contoh loop device
          @foreach($data as $item)
              @php
                  $fDate = $item->TANGGAL ?? null;
                  $valData = $item->PRESSURE ?? 0;
                  $standartBlock = [10,17,30]; // contoh range
              @endphp
              arrGauge["{{ $item->DEVICE_ID }}"] = drawGaugeChart(
                  '{{ $item->DEVICE_ID }}',
                  '{{ "lastUpdate".str_replace("-", "", $item->DEVICE_ID) }}',
                  {{ $valData }},
                  '{{ $fDate }}',
                  '{{ $item->TITLE_PAGE }}',
                  '{{ $item->SATUAN }}',
                  @json($standartBlock)
              );
          @endforeach
      });
  </script>
@endpush
