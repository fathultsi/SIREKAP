<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rekap Uang Makan</title>
    <link rel="stylesheet" href="{{ public_path('templates/dist/uangmakan/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }

        .fontable thead th {
            font-weight: bold;
            font-size: 12px;
        }

        .fontable tbody {
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <h4>Rekap Kehadiran: {{ $pegawai['nama'] }} ({{ $pegawai['nip'] }})</h4>
    <table class="table table-bordered fontable">
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
            <tr @if (!empty($d['keterangan']) && $d['keterangan'] !=='-' ) style="background-color:#FAEDD7" @endif>
                <td>{{ $d['tanggal'] }}</td>
                <td>{{ $d['hari'] }}</td>
                <td>{{ $d['jam_masuk'] }}</td>
                <td>{{ $d['absen_masuk'] }}</td>
                <td>{{ $d['jam_pulang'] }}</td>
                <td>{{ $d['absen_pulang'] }}</td>
                <td>{{ $d['uang_per_hari'] }}</td>
                <td>{{ $d['keterangan'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Jumlah Kotor</td>
                <td colspan="2">{{ $pegawai['jumlah_kotor'] }}</td>
            </tr>
            <tr>
                <td colspan="6">Potongan Pajak</td>
                <td colspan="2">{{ $pegawai['potongan_pajak'] }}</td>
            </tr>
            <tr>
                <td colspan="6"><strong>Total</strong></td>
                <td colspan="2"><strong>{{ $pegawai['total_bersih'] }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>