@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-end mb-3">
        <!-- Group untuk Date Picker -->
        <div class="me-3">
            <label for="filterDate" class="form-label">Pilih Tanggal:</label>
            <input type="text" id="filterDate" class="form-control" placeholder="17 March 2025" autocomplete="off">
        </div>

        <!-- Tombol Print PDF -->
        <div class="">
            <button id="printPdf" class="btn btn-primary" style="margin-bottom: 0;">
                <i class="bi bi-printer"></i> Print PDF
            </button>
        </div>
    </div>

    <!-- Konten lain (misalnya gauge, dsb.) -->

@endsection

@push('page_scripts')
    <!-- Inisialisasi Datepicker -->
    <script>
        $(document).ready(function() {
            // Aktifkan datepicker dengan format 'dd MM yyyy'
            $('#filterDate').datepicker({
                format: 'dd MM yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // Event klik tombol Print PDF
            $('#printPdf').click(function() {
                // Ambil tanggal yang dipilih
                let selectedDate = $('#filterDate').val();
                
                // Jika Anda perlu kirim tanggal ke controller, 
                // bisa menambahkan parameter ?date= di URL, misalnya:
                // window.open('/ptpn/device/pdf/{{ $pks }}?date=' + encodeURIComponent(selectedDate), '_blank');
                
                // Jika tidak perlu filter tanggal, cukup:
                window.open('/ptpn/device/pdf/{{ $pks }}', '_blank');
            });
        });
    </script>
@endpush
