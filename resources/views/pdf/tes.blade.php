<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        thead th {
            background-color: #f3f3f3;
            font-weight: bold;
        }

        .libur {
            background-color: #fff7e6;
            /* kuning pucat */
            font-weight: bold;
        }

        .tanpa-keterangan {
            color: red;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <table>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>HARI</th>
                <th>JAM MASUK</th>
                <th>ABSEN MASUK</th>
                <th>TERLAMBAT / CEPAT (Menit)</th>
                <th>JAM PULANG</th>
                <th>ABSEN PULANG</th>
                <th>PSW / LEWAT WAKTU (Menit)</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <tr class="libur">
                <td>01-06-2025</td>
                <td>Minggu</td>
                <td colspan="7">Hari Lahir Pancasila</td>
            </tr>
            <tr>
                <td style="color:red;">02-06-2025</td>
                <td>Senin</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="tanpa-keterangan">Tanpa keterangan</td>
            </tr>
            <tr>
                <td style="color:red;">03-06-2025</td>
                <td>Selasa</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="tanpa-keterangan">Tanpa keterangan</td>
            </tr>
            <tr class="libur">
                <td>05-06-2025</td>
                <td>Kamis</td>
                <td colspan="7">Hari Raya Idul Adha 1446 H</td>
            </tr>
            <tr>
                <td>06-06-2025</td>
                <td>Jumat</td>
                <td>07:00</td>
                <td></td>
                <td></td>
                <td>07:00</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="libur">
                <td>09-06-2025</td>
                <td>Senin</td>
                <td colspan="7">Cuti Bersama Hari Raya Idul Adha 1446 H</td>
            </tr>
            <tr>
                <td>10-06-2025</td>
                <td>Selasa</td>
                <td>07:30</td>
                <td>07:30</td>
                <td class="text-right">0</td>
                <td>16:00</td>
                <td>16:00</td>
                <td class="text-right">0</td>
                <td></td>
            </tr>
            <tr>
                <td>11-06-2025</td>
                <td>Rabu</td>
                <td>07:30</td>
                <td>07:30</td>
                <td class="text-right">0</td>
                <td>16:00</td>
                <td>16:05</td>
                <td class="text-right">5</td>
                <td class="tanpa-keterangan">Tanpa keterangan</td>
            </tr>
            <tr>
                <td style="color:red;">13-06-2025</td>
                <td>Jumat</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="tanpa-keterangan">Tanpa keterangan</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
