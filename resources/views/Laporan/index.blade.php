<!-- resources/views/laporan/index.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generator Laporan Boiler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">PTPN V | PKS SEI INTAN - Laporan Boiler</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('boiler.laporan.pdf') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_perangkat" class="form-label">ID Perangkat</label>
                                <select class="form-select" id="id_perangkat" name="id_perangkat" required>
                                    <option value="BOILER NOMOR 1">BOILER NOMOR 1</option>
                                    <option value="BOILER NOMOR 2">BOILER NOMOR 2</option>
                                    <option value="BOILER NOMOR 3">BOILER NOMOR 3</option>
                                </select>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Buat Laporan PDF</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>