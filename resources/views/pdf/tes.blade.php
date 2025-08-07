<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rekap Uang Makan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link rel="stylesheet" href="{{ public_path('templates/dist/uangmakan/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('templates/dist/uangmakan/bootstrap.min.css') }}">
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

        /* Tambahan dari app.min.css */
        .table-card {
            box-shadow: 0 0 13px 0 rgba(236, 238, 239, 0.5);
            border-radius: 0.42rem;
            border: 1px solid #e9ebfa;
            overflow-x: auto;
        }
    </style>

</head>

{{-- cek apakah file ada echo file_exists(public_path('templates/dist/uangmakan/bootstrap.min.css')) ? 'ADA' : 'TIDAK ADA'; --}}
@php
    echo file_exists(public_path('templates/dist/uangmakan/bootstrap.min.css')) ? 'ADA' : 'TIDAK ADA';

@endphp

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Data Kehadiran</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-bordered table-striped fontable">
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
                                    <tr class="table-warning">
                                        <td>01-06-2025</td>
                                        <td>Minggu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td><b>Hari Lahir Pancasila</b></td>
                                    </tr>
                                    <tr class="table-light">
                                        <td style="color: red;">02-06-2025</td>
                                        <td style="color: red;">Senin</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="color: red;">Tanpa Keterangan</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td style="color: red;">03-06-2025</td>
                                        <td style="color: red;">Selasa</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="color: red;">Tanpa Keterangan</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td style="color: red;">04-06-2025</td>
                                        <td style="color: red;">Rabu</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="color: red;">Tanpa Keterangan</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td style="color: red;">05-06-2025</td>
                                        <td style="color: red;">Kamis</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="color: red;">Tanpa Keterangan</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>06-06-2025</td>
                                        <td>Jum'at</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td><b>Hari Raya Idul Adha 1446 Hijriah</b></td>
                                    </tr>
                                    <tr>
                                        <td>07-06-2025</td>
                                        <td>Sabtu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>08-06-2025</td>
                                        <td>Minggu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>09-06-2025</td>
                                        <td>Senin</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td><b>Cuti Bersama Hari Raya Idul Adha 1446 Hijriah</b></td>
                                    </tr>
                                    <tr>
                                        <td>10-06-2025</td>
                                        <td>Selasa</td>
                                        <td>07:30</td>
                                        <td>07:00</td>
                                        <td>16:00</td>
                                        <td>16:00</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>11-06-2025</td>
                                        <td>Rabu</td>
                                        <td>07:30</td>
                                        <td>07:25</td>
                                        <td>16:00</td>
                                        <td>16:36</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>12-06-2025</td>
                                        <td>Kamis</td>
                                        <td>07:30</td>
                                        <td>07:25</td>
                                        <td>16:00</td>
                                        <td>16:17</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>13-06-2025</td>
                                        <td>Jum'at</td>
                                        <td>07:30</td>
                                        <td>07:25</td>
                                        <td>16:30</td>
                                        <td>20:02</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>14-06-2025</td>
                                        <td>Sabtu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>15-06-2025</td>
                                        <td>Minggu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>16-06-2025</td>
                                        <td>Senin</td>
                                        <td>07:30</td>
                                        <td>07:28</td>
                                        <td>16:00</td>
                                        <td>16:07</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>17-06-2025</td>
                                        <td>Selasa</td>
                                        <td>07:30</td>
                                        <td>07:24</td>
                                        <td>16:00</td>
                                        <td>16:07</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>18-06-2025</td>
                                        <td>Rabu</td>
                                        <td>07:30</td>
                                        <td>07:27</td>
                                        <td>16:00</td>
                                        <td>16:01</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>19-06-2025</td>
                                        <td>Kamis</td>
                                        <td>07:30</td>
                                        <td>07:26</td>
                                        <td>16:00</td>
                                        <td>16:11</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>20-06-2025</td>
                                        <td>Jum'at</td>
                                        <td>07:30</td>
                                        <td>07:01</td>
                                        <td>16:30</td>
                                        <td>16:41</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>21-06-2025</td>
                                        <td>Sabtu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>22-06-2025</td>
                                        <td>Minggu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>23-06-2025</td>
                                        <td>Senin</td>
                                        <td>07:30</td>
                                        <td>07:18</td>
                                        <td>16:00</td>
                                        <td>16:33</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>24-06-2025</td>
                                        <td>Selasa</td>
                                        <td>07:30</td>
                                        <td>07:23</td>
                                        <td>16:00</td>
                                        <td>16:05</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>25-06-2025</td>
                                        <td>Rabu</td>
                                        <td>07:30</td>
                                        <td>07:27</td>
                                        <td>16:00</td>
                                        <td>16:44</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>26-06-2025</td>
                                        <td>Kamis</td>
                                        <td>07:30</td>
                                        <td>07:26</td>
                                        <td>16:00</td>
                                        <td>17:34</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>27-06-2025</td>
                                        <td>Jum'at</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td><b>Tahun Baru Islam 1447 Hijriah (1 Muharam)</b></td>
                                    </tr>
                                    <tr>
                                        <td>28-06-2025</td>
                                        <td>Sabtu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>29-06-2025</td>
                                        <td>Minggu</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>07:00</td>
                                        <td>-</td>
                                        <td>0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>30-06-2025</td>
                                        <td>Senin</td>
                                        <td>07:30</td>
                                        <td>07:29</td>
                                        <td>16:00</td>
                                        <td>16:01</td>
                                        <td>35.150</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Jumlah Kotor</th>
                                        <th>518.000</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Potongan Pajak</th>
                                        <th>25.900</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Total</th>
                                        <th>492.100</th>
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
