<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rekap Uang Makan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ public_path('templates/dist/uangmakan/bootstrap.min.css') }}">
    <style>
        body {
            font-size: 12px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .fontable thead th {
            font-weight: bold;
            font-size: 12px;
        }

        .fontable tbody {
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dee2e6;
        }

        .table-warning {
            background-color: #fff3cd;
        }

        .table-light {
            background-color: #f8f9fa;
        }

        .text-danger {
            color: red;
        }

        .container-fluid {
            padding: 1rem;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .card-body {
            padding: 1rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Rekap Kehadiran: {{ $pegawai['nama'] }} ({{ $pegawai['nip'] }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-striped fontable">
                                <thead>
                                    <tr>
                                        <th>TANGGAL</th>
                                        <th>HARI</th>
                                        <th>JAM MASUK</th>
                                        <th>ABSEN MASUK</th>
                                        <th>JAM PULANG</th>
                                        <th>ABSEN PULANG</th>
                                        <th>UANG MAKAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pegawai['detail'] as $d)
                                        @php
                                            $warning = !empty($d['keterangan']) && $d['keterangan'] !== '-';
                                            $danger = strtolower($d['keterangan']) === 'tanpa keterangan';
                                        @endphp
                                        <tr class="{{ $warning ? 'table-warning' : 'table-light' }}">
                                            <td class="{{ $danger ? 'text-danger' : '' }}">{{ $d['tanggal'] }}</td>
                                            <td class="{{ $danger ? 'text-danger' : '' }}">{{ $d['hari'] }}</td>
                                            <td>{{ $d['jam_masuk'] }}</td>
                                            <td>{{ $d['absen_masuk'] }}</td>
                                            <td>{{ $d['jam_pulang'] }}</td>
                                            <td>{{ $d['absen_pulang'] }}</td>
                                            <td>{{ $d['uang_per_hari'] }}</td>
                                            <td class="{{ $danger ? 'text-danger' : '' }}">
                                                @if ($warning)
                                                    <b>{{ $d['keterangan'] }}</b>
                                                @else
                                                    {{ $d['keterangan'] }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Jumlah Kotor</th>
                                        <th>{{ $pegawai['jumlah_kotor'] }}</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Potongan Pajak</th>
                                        <th>{{ $pegawai['potongan_pajak'] }}</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Total</th>
                                        <th>{{ $pegawai['total_bersih'] }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
