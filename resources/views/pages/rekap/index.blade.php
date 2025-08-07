@extends('layouts2.main')

@section('DataTables')
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <style>
        .db-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            position: relative;
            background: white;
        }

        .db-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
        }

        .db-card-header {
            padding: 20px 20px 0;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .db-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
            color: white;
        }

        .db-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: #4a4a4a;
        }

        .db-card-body {
            padding: 0 20px 15px;
        }

        .db-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .db-card-desc {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 5px 0 0;
        }

        .db-card-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(0, 0, 0, 0.02);
        }

        /* Color Variants */
        .db-primary .db-card-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .db-warning .db-card-icon {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        }

        .db-danger .db-card-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .db-info .db-card-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }


        .db-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .db-dark {
            background-color: #343a40;
            color: white;
        }

        .db-light {
            background-color: #f8f9fa;
            color: #212529;
        }

        .db-card-link {
            color: #4a4a4a;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s;
        }

        .db-card-link:hover {
            color: #007bff;
        }

        .db-card-link i {
            margin-left: 5px;
            font-size: 0.8rem;
            transition: transform 0.3s;
        }

        .db-card-link:hover i {
            transform: translateX(3px);
        }

        .db-workday-vertical {
            margin: 10px 0;
        }

        .db-workday-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
        }

        .db-workday-days {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .db-workday-count {
            font-weight: 600;
            color: #4a4a4a;
        }
    </style>
@endsection


@section('content')
    <div class="container pt-4">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <button onclick="previewSatuPegawai()" class="btn btn-warning">Preview Satu Pegawai</button>
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Upload File Excel</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <div class="custom-file me-2" style="flex: 1;">
                                <input type="file" class="custom-file-input" id="excelFile" accept=".xlsx" multiple>
                                <label class="custom-file-label" for="excelFile">Choose file</label>
                            </div>
                            <a href="/halamanRekap" class="ml-2 btn btn-warning">Reset</a>
                        </div>
                    </div>


                </div>

            </div>
        </div>

        <div class="row">
            <!-- Hari Kerja Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="db-card db-primary">
                    <div class="db-card-header">
                        <div class="db-card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="db-card-title">Hari Kerja</h3>
                    </div>
                    <div class="db-card-body">
                        <div class="db-workday-vertical">
                            <div class="db-workday-row">
                                <span class="db-workday-days">5 Hari/Minggu:</span>
                                <span class="db-workday-count" id="hari_kerja5">0</span>
                            </div>
                            <div class="db-workday-row">
                                <span class="db-workday-days">6 Hari/Minggu:</span>
                                <span class="db-workday-count" id="hari_kerja6">0</span>
                            </div>
                        </div>
                        <p class="db-card-desc">Total hari kerja bulan <span class="ket_tanggal"></span></p>
                    </div>
                </div>
            </div>

            <!-- Libur Nasional Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="db-card db-warning">
                    <div class="db-card-header">
                        <div class="db-card-icon">
                            <i class="fas fa-umbrella-beach"></i>
                        </div>
                        <h3 class="db-card-title">Libur Nasional</h3>
                    </div>
                    <div class="db-card-body">
                        <h2 class="db-card-value" id="total_hari_libur">0</h2>
                        <p class="db-card-desc">hari bulan <span class="ket_tanggal"></span></p>
                    </div>

                    <div class="db-card-footer">
                        <a href="#" id="btn_tinjau_libur" class="db-card-link">Tinjau Data <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Data Duplikat Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="db-card db-danger">
                    <div class="db-card-header">
                        <div class="db-card-icon">
                            <i class="fas fa-clone"></i>
                        </div>
                        <h3 class="db-card-title">Data Duplikat</h3>
                    </div>
                    <div class="db-card-body">
                        <h2 class="db-card-value" id="total_duplikat">0</h2>
                        <p class="db-card-desc">entri terdeteksi</p>
                    </div>
                    <div class="db-card-footer">
                        <a href="#" id="btn_tinjau_duplikat" class="db-card-link">Tinjau Data <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Masuk Saat Libur Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="db-card db-info">
                    <div class="db-card-header">
                        <div class="db-card-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="db-card-title">Absensi Saat Libur</h3>
                    </div>
                    <div class="db-card-body">
                        <h2 class="db-card-value" id="total_masuk_libur">0</h2>
                        <p class="db-card-desc">Data</p>
                    </div>
                    <div class="db-card-footer">
                        <a href="#" id="btn_tinjau_masuk_libur" class="db-card-link">Tinjau Data <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- navbar --}}
        <div class="row">
            <div class="card card-primary card-outline card-outline-tabs w-100">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                aria-selected="true">Kehadiran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                                aria-selected="false">Uang Makan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill"
                                href="#custom-tabs-four-messages" role="tab"
                                aria-controls="custom-tabs-four-messages" aria-selected="false">Tukin</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                            aria-labelledby="custom-tabs-four-home-tab">
                            <div class="row">
                                <div class="card  w-100">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Rekapitulasi Absensi Pegawai <span
                                                class="ket_tanggal"></span></h3>
                                        <div class="card-tools">
                                            <button id="btn_export" class="btn btn-tool btn-sm bg-info">
                                                <i class="fas fa-download"></i>
                                                <span>Export</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="rekapTable"
                                                    class="table table-bordered table-hover bg-white shadow-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th class="w-25">Nama</th>
                                                            <th>NIP</th>
                                                            <th>Hari Masuk</th>
                                                            <th>Telat/Cepat Pulang</th>
                                                            <th>Absen Sekali</th>
                                                            <th>Cuti</th>
                                                            <th>Dinas/lainnya</th>
                                                            <th>Tanpa Keterangan</th>
                                                            <th>Absensi Saat Libur</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-four-profile-tab">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card w-100">
                                        {{-- <div class="card-header border-0">
                                            <h3 class="card-title">Rekapitulasi Uang Makan <span
                                                    class="ket_tanggal"></span></h3>
                                            <div class="card-tools">
                                                <button id="btn_exp_uang_makan" class="btn btn-tool btn-sm bg-info">
                                                    <i class="fas fa-download"></i>
                                                    <span>Export</span>
                                                </button>
                                                <button id="btn_exp_uangmakan_zip" class="btn btn-tool btn-sm bg-info">
                                                    <i class="fas fa-download"></i>
                                                    <span>Export zip</span>
                                                </button>
                                                <div id="status" class="mt-3"></div>
                                                <div style="width: 100%; background: #ddd; height: 20px;">
                                                    <div id="progress-bar"
                                                        style="width: 0%; height: 100%; background: #28a745;"></div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div
                                            class="card-header border-0 d-flex justify-content-between align-items-center flex-wrap">
                                            <h3 class="card-title mb-2 mb-sm-0">
                                                Rekapitulasi Uang Makan <span
                                                    class="ket_tanggal text-muted font-weight-normal"></span>
                                            </h3>

                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                <button id="btn_exp_uang_makan"
                                                    class="btn btn-sm btn-outline-primary mr-2 mb-2">
                                                    <i class="fas fa-file-export"></i> Export
                                                </button>
                                                <button id="btn_exp_uangmakan_zip"
                                                    class="btn btn-sm btn-outline-success mb-2">
                                                    <i class="fas fa-file-archive"></i> Export ZIP
                                                </button>
                                            </div>
                                        </div>

                                        <div class="px-3 pb-3">
                                            <div id="status" class="mb-2 text-info font-weight-bold">
                                            </div>

                                            <div class="progress" style="height: 20px;">
                                                <div id="progress-bar" class="progress-bar bg-success" role="progressbar"
                                                    style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="table_rekap_uang_makan"
                                                class="table table-bordered table-hover bg-white shadow-sm table-sm  w-100">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>NIP</th>
                                                        <th class="w-25">Nama</th>
                                                        <th>Gol/Ruang</th>
                                                        <th>Hari Masuk</th>
                                                        <th>Uang_Makan</th>
                                                        <th>PPH_____</th>
                                                        <th>Jumlah_Bersih</th>
                                                        <th>Status</th>
                                                        {{-- <th>Satker 2</th>
                                                <th>Satker 3</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- tabel rekap tabel uang makan --}}

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                            aria-labelledby="custom-tabs-four-messages-tab">

                        </div>
                    </div>
                </div>
            </div>

        </div>



    </div>

    @include('pages.rekap.modals')
@endsection

@section('scripts_DataTables')
    <script src="{{ asset('templates/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/Sheetjs/xlsx.full.min.js') }}"></script>
    {{-- <script src="{{ asset('templates/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> --}}
@endsection

@section('scripts')
    <script>
        var dataPegawai = [];
        var untukExportRekap = [];
        var hasilrekapuangmakan = [];
        var bulanTahun = "";
        var dataExportTanpaKeterangan = [];
        var dataExportAbsensiSekali = [];
        const nip6HariKerja = [
            "196812312005011067",
            "197002102006042001",
            "198007132014101003"
        ];

        $(document).ready(function() {
            $('#rekapTable').DataTable({
                columnDefs: [{
                    targets: [3, 4, 5, 6, 7, 8, 9], // kolom ke-4 sampai ke-10
                    className: 'text-start' // bootstrap class untuk left-align
                }]
            });
            $("#excelFile").on("change", function(e) {
                const files = e.target.files;
                if (!files.length) return;

                // Buat variabel penampung
                let dataAbsensi = [];
                let dataUangMakan = [];
                let dataTunjangan = [];

                let filesLoaded = 0;

                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, {
                            type: "array"
                        });

                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];

                        const jsonData = XLSX.utils.sheet_to_json(sheet, {
                            header: 1,
                            defval: "",
                        });

                        const headers = jsonData[0];
                        const rows = jsonData.slice(1).map(row => {
                            let obj = {};
                            headers.forEach((key, i) => {
                                obj[key] = row[i];
                            });
                            return obj;
                        });

                        // Deteksi berdasarkan nama file
                        const filename = file.name.toLowerCase();

                        if (filename.includes("kehadiran")) {
                            dataAbsensi = rows;
                        } else if (filename.includes("uang makan")) {
                            dataUangMakan = rows;
                        } else if (filename.includes("tukin")) {
                            dataTunjangan = rows;
                        }

                        filesLoaded++;
                        // Jalankan setelah semua file selesai dibaca
                        if (filesLoaded === files.length) {
                            prosesSemuaFile(dataAbsensi, dataUangMakan, dataTunjangan);
                        }
                    };

                    reader.readAsArrayBuffer(file);
                });
            });



            function prosesSemuaFile(dataAbsensi, dataUangMakan, dataTunjangan) {
                console.log("ABSENSI:", dataAbsensi);
                console.log("UANG MAKAN:", dataUangMakan);
                console.log("TUNJANGAN:", dataTunjangan);

                // Proses absensi
                if (dataAbsensi.length > 0) {
                    const cekduplikat = hapusDuplikatAbsensi(dataAbsensi);
                    if (cekduplikat.duplikat.length > 0) {
                        generateModalDuplikat(cekduplikat.duplikat);
                    }

                    rekapHariKerjaDanLibur(cekduplikat.unique);
                    formatBulanTahunDariData(cekduplikat.unique);

                    dataPegawai = prosesDanGroupAbsensi(cekduplikat.unique);
                    dataPegawai = urutkanPegawai(dataPegawai);
                    inisialisasiRekap(dataPegawai);
                }

                // Proses uang makan
                if (dataUangMakan.length > 0) {
                    // TODO: proses uang makan
                    console.log("Proses uang makan di sini");
                    generateTableUangMakan(dataUangMakan);

                    //handel rekap uang makan
                    let datamerge = joinUangMakanDenganAbsensi(dataPegawai, dataUangMakan);
                    console.log("datamerge", datamerge);
                    hasilrekapuangmakan = mappingDataAbsensi(datamerge);
                    console.log("hasilrekapuangmakan", hasilrekapuangmakan);
                }

                // Proses tunjangan
                if (dataTunjangan.length > 0) {
                    // TODO: proses tunjangan
                    console.log("Proses tunjangan di sini");
                }
            }



            $('#rekapTable tbody').on('click', '.clickable', function() {
                const index = $(this).data('index');
                const key = $(this).data('key');
                tampilkanDetail(index, key, dataPegawai);
            });

        });


        function prosesDanGroupAbsensi(data) {
            const grouped = {};
            let forDetailMASUK_SAAT_LIBUR = [];
            data.forEach(item => {
                const {
                    NAMA,
                    NIP,
                    JABATAN,
                    SATKER_1 = "",
                    SATKER_2 = ""
                } = item;

                const STATUS_PEGAWAI = item["STATUS PEGAWAI"]; // 👈 perbaikan di sini

                const key = `${NAMA}_${NIP}_${JABATAN}_${SATKER_1}_${SATKER_2}_${STATUS_PEGAWAI}`;
                if (!grouped[key]) {
                    grouped[key] = {
                        NAMA,
                        NIP,
                        JABATAN,
                        SATKER_1,
                        SATKER_2,
                        STATUS_PEGAWAI,
                        detail: [],
                        libur: [],
                        cuti: [],
                        dinas: [],
                        tugasLainnya: [],
                        terlambat: [],
                        masukKerja: [],
                        masukSaatLibur: [],
                        absenSekali: [],
                        tanpaKeterangan: []
                    };
                }

                const hari = item.HARI;
                const jenisTugas = item["JENIS TUGAS"]?.toLowerCase().trim() || "";
                // const isHariLibur = ["sabtu", "minggu"].includes(hari.toLowerCase()) || item.LIBUR?.trim() !== "";
                const isHariSabtu = item.HARI?.toLowerCase() === "sabtu";
                const isHariMinggu = item.HARI?.toLowerCase() === "minggu";
                const liburNasional = item.LIBUR?.trim() !== "";
                const is6Hari = nip6HariKerja.includes(item.NIP);

                // ✅ Hanya pegawai 5 hari kerja yang libur di Sabtu
                const isHariLibur = isHariMinggu || liburNasional || (isHariSabtu && !is6Hari);

                grouped[key].detail.push(item);

                if (isHariLibur) {
                    grouped[key].libur.push(item);
                    const adaAbsen = item["ABSEN MASUK"] || item["ABSEN PULANG"];
                    if (adaAbsen) {
                        grouped[key].masukSaatLibur.push(item);
                        forDetailMASUK_SAAT_LIBUR.push(item);
                    };
                    return;
                }

                if (jenisTugas.includes("cuti")) {
                    grouped[key].cuti.push(item);
                    return;
                }

                // if (jenisTugas.includes("dinas")) {
                if (jenisTugas !== "") { // saya tdk filter by kata dinas, karena ada dinas lainnya
                    grouped[key].dinas.push(item);
                    return;
                }

                if (jenisTugas !== "") {
                    grouped[key].tugasLainnya.push(item);
                    return;
                }

                const cepatTelat = typeof item["CEPAT TELAT"] === "number" ? item["CEPAT TELAT"] : parseFloat(item[
                    "CEPAT TELAT"]) || 0;
                const psw = typeof item["PSW"] === "number" ? item["PSW"] : parseFloat(item["PSW"]) || 0;

                const isTerlambatMasuk = item["ABSEN MASUK"] && cepatTelat < 0;
                const isPulangCepat = item["ABSEN PULANG"] && psw < 0;
                const isTelat = isTerlambatMasuk || isPulangCepat;

                const isAbsenSekali =
                    (item["ABSEN MASUK"] && !item["ABSEN PULANG"]) ||
                    (!item["ABSEN MASUK"] && item["ABSEN PULANG"]);

                const isTanpaKeterangan = item["KETERANGAN 2"]?.trim() === "Tanpa Keterangan";

                if (!isTanpaKeterangan) {
                    grouped[key].masukKerja.push(item);
                }

                if (isTelat) grouped[key].terlambat.push(item);
                if (isAbsenSekali) {
                    dataExportAbsensiSekali.push(item);
                    grouped[key].absenSekali.push(item)
                };
                if (isTanpaKeterangan) {
                    dataExportTanpaKeterangan.push(item);
                    grouped[key].tanpaKeterangan.push(item);
                };
            });
            //untuk rekap yang masuk hari libur
            if (forDetailMASUK_SAAT_LIBUR.length > 0) generateModalabsen_libur(forDetailMASUK_SAAT_LIBUR);
            return Object.values(grouped);
        }
    </script>
    <script>
        const kategoriKolom = [{
                key: 'masukKerja',
                label: 'Hari Masuk'
            },
            {
                key: 'terlambat',
                label: 'Telat/Cepat Pulang'
            },
            {
                key: 'absenSekali',
                label: 'Absen Sekali'
            },
            {
                key: 'cuti',
                label: 'Cuti'
            },
            {
                key: 'dinas',
                label: 'Dinas'
            },
            {
                key: 'tanpaKeterangan',
                label: 'Tanpa Keterangan'
            },
            {
                key: 'masukSaatLibur',
                label: 'Masuk Saat Libur'
            }
        ];

        function inisialisasiRekap(data) {
            if ($.fn.DataTable.isDataTable('#rekapTable')) {
                $('#rekapTable').DataTable().clear().destroy();
            }
            let newData = [];
            console.log(data);
            const table = $('#rekapTable').DataTable({
                columnDefs: [{ // kolom ke-4 sampai ke-10
                    targets: [3, 4, 5, 6, 7, 8, 9],
                    className: 'dt-body-right' // Bawaan DataTables untuk align right
                }]
            });
            table.clear(); // reset tabel jika sudah pernah diisi
            let no_table = 1;
            data.forEach((pegawai, index) => {
                const row = [
                    no_table++,
                    pegawai.NAMA,
                    pegawai.NIP,
                    ...kategoriKolom.map(k => renderClickable(pegawai[k.key], index, k.key))
                ];
                table.row.add(row);
                newData.push({
                    no_table,
                    NAMA: pegawai.NAMA,
                    NIP: pegawai.NIP,
                    H_MASUK: pegawai.masukKerja.length,
                    TELAT_CEPAT_PULANG: pegawai.terlambat.length,
                    ABSEN_SEKALI: pegawai.absenSekali.length,
                    DINAS: pegawai.dinas.length,
                    CUTI: pegawai.cuti.length,
                    TANPA_KETERANGAN: pegawai.tanpaKeterangan.length,
                    MASUK_SAAT_LIBUR: pegawai.masukSaatLibur.length
                });


            });
            untukExportRekap = newData;
            table.draw();


        }

        function renderClickable(array, index, key) {
            const jumlah = array.length;
            return jumlah > 0 ?
                `<span class="clickable p-1" data-index="${index}" data-key="${key}">${jumlah}</span>` :
                "0";
        }

        function tampilkanDetail(index, key, dataPegawai) {
            const pegawai = dataPegawai[index];
            const data = pegawai[key];
            let no_urut = 1;
            let html = `<h5>${pegawai.NAMA} - ${labelKategori(key)}</h5>`;
            if (!data.length) {
                html += `<p class="text-muted">Tidak ada data</p>`;
            } else {

                html += `
      <table class="table table-sm table-bordered mt-3">
        <thead class="thead-light">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Absen Masuk</th>
            <th>Absen Pulang</th>
            <th>Jenis Tugas</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          ${data.map(row => `<tr><td>${no_urut++}</td> <td>${row.TANGGAL || "-"}</td><td>${row.HARI || "-"}</td> <td>${row["ABSEN MASUK"] || "-"}</td><td>${row["ABSEN PULANG"] || "-"}</td> <td>${row["JENIS TUGAS"] || "-"}</td><td>${row["KETERANGAN"] || row["KETERANGAN 2"]}</td> </tr>`).join('')} 
        </tbody>
      </table>
    `;
            }

            $('#modalContent').html(html);
            $('#detailModal').modal('show');
        }

        function labelKategori(key) {
            const label = kategoriKolom.find(k => k.key === key);
            return label ? label.label : key;
        }

        function urutkanPegawai(dataPegawai) {
            const prioritasNIP = [
                "196812311998031016",
                "197101011997032002",
                "199503012020122016",
                "197206182014111001",
                "197802062014121001",
                "198310102009121007",
                "196611182005011002",
                "196812312005011069",
                "197611282007101001",
                "197405182006041019",
                "196912312014111018",
                "198002032009121001"
            ];

            // Bagi dua kelompok: prioritas dan non-prioritas
            const pejabat = [];
            const lainnya = [];

            dataPegawai.forEach(p => {
                if (prioritasNIP.includes(p.NIP)) {
                    pejabat.push(p);
                } else {
                    lainnya.push(p);
                }
            });

            // Urutkan pejabat sesuai urutan prioritas
            pejabat.sort((a, b) => {
                return prioritasNIP.indexOf(a.NIP) - prioritasNIP.indexOf(b.NIP);
            });

            // Urutkan sisanya berdasarkan nama
            lainnya.sort((a, b) => a.NAMA.localeCompare(b.NAMA));

            return [...pejabat, ...lainnya];
        }

        // function hapusDuplikatAbsensi(rows) {
        //     const seen = new Set();
        //     const unique = [];
        //     const duplikat = [];

        //     console.log("rows", rows);

        //     rows.forEach(row => {
        //         const key = `${row.NIP}_${row.TANGGAL}_${row.HARI}`.trim();
        //         if (seen.has(key)) {
        //             duplikat.push(row); // simpan data duplikat untuk referensi
        //         } else {
        //             seen.add(key);
        //             unique.push(row);
        //         }
        //     });

        //     return {
        //         unique,
        //         duplikat
        //     };
        // }
        function hapusDuplikatAbsensi(rows) {
            const seen = new Set();
            const unique = [];
            const duplikat = [];

            console.log("rows", rows);

            rows.forEach(row => {
                // Potong TANGGAL menjadi hanya bagian tanggal (yyyy-mm-dd)
                row.TANGGAL = row.TANGGAL.split(' ')[0];

                const key = `${row.NIP}_${row.TANGGAL}_${row.HARI}`.trim();

                if (seen.has(key)) {
                    duplikat.push(row); // simpan data duplikat untuk referensi
                } else {
                    seen.add(key);
                    unique.push(row);
                }
            });

            return {
                unique,
                duplikat
            };
        }

        function rekapHariKerjaDanLibur(rows) {

            // Hanya ambil satu data per tanggal (untuk efisiensi)
            const dataPerTanggal = {};

            rows.forEach(row => {
                const tanggal = row.TANGGAL?.split(' ')[0];
                if (!tanggal) return;
                if (!dataPerTanggal[tanggal]) {
                    dataPerTanggal[tanggal] = {
                        tanggal,
                        hari: row.HARI,
                        libur: row.LIBUR?.trim() || ""
                    };
                }
            });

            // Hasil akhir
            const liburResmi = [];
            let totalHariKerja5 = 0;
            let totalHariKerja6 = 0;
            let totalLibur = 0;

            Object.values(dataPerTanggal).forEach(({
                tanggal,
                hari,
                libur
            }) => {
                const hariLower = hari?.toLowerCase();

                const isMinggu = hariLower === "minggu";
                const isSabtu = hariLower === "sabtu";
                const isSeninSampaiSabtu = ["senin", "selasa", "rabu", "kamis", "jumat", "sabtu"].includes(
                    hariLower);

                if (libur && isSeninSampaiSabtu) {
                    liburResmi.push({
                        tanggal,
                        hari,
                        alasan: libur
                    });
                    totalLibur++;
                }

                // Hitung hari kerja
                const isLibur5 = isMinggu || (isSabtu) || (libur && isSeninSampaiSabtu);
                const isLibur6 = isMinggu || (libur && isSeninSampaiSabtu);

                if (!isLibur5) totalHariKerja5++;
                if (!isLibur6) totalHariKerja6++;
            });


            //hari_kerja5
            $("#hari_kerja5").text(`${totalHariKerja5} Hari`);
            //hari_kerja6
            $("#hari_kerja6").text(`${totalHariKerja6} Hari`);
            $("#total_hari_libur").text(liburResmi.length);

            //masukan liburResmi tbody_hari_libur 
            $("#tbody_hari_libur").html("");
            liburResmi.forEach((item, index) => {
                $("#tbody_hari_libur").append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.tanggal}</td>
                        <td>${item.hari}</td>
                        <td>${item.alasan}</td>
                    </tr>
                `);
            });
        }

        function generateModalDuplikat(data) {
            $("#total_duplikat").text(data.length);
            $("#tbody_duplikat").html("");
            data.forEach((item, index) => {
                $("#tbody_duplikat").append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.NAMA}</td>
                        <td>${item.NIP}</td>
                        <td>${item.TANGGAL}</td>
                        <td>${item.HARI}</td>
                        <td>${item["ABSEN MASUK"]}</td>
                        <td>${item["ABSEN PULANG"]}</td>
                        <td>${item["JENIS TUGAS"]}</td>
                        <td>${item["KETERANGAN"]}</td>
                    </tr>
                `);
            });
        }

        function generateModalabsen_libur(data) {

            $("#total_masuk_libur").text(data.length);
            $("#tbody_absen_libur").html("");
            data.forEach((item, index) => {
                $("#tbody_absen_libur").append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.NAMA}</td>
                        <td>${item.NIP}</td>
                        <td>${item.TANGGAL}</td>
                        <td>${item.HARI}</td>
                        <td>${item["ABSEN MASUK"]}</td>
                        <td>${item["ABSEN PULANG"]}</td>
                        <td>${item.LIBUR}</td>
                    </tr>
                `);
            });
        }
        $(document).ready(function() {
            $('#btn_tinjau_libur').click(function() {
                $('#modal_hari_libur').modal('show');
            });
            $('#btn_tinjau_duplikat').click(function() {
                $('#modal_duplikat').modal('show');
            });
            $('#btn_tinjau_masuk_libur').click(function() {
                $('#modal_absen_libur').modal('show');
            });
        });

        function formatBulanTahunDariData(rows) {
            if (!rows.length) return "";

            const tanggal = rows[0].TANGGAL?.split(" ")[0]; // Ambil bagian '2025-06-01'
            if (!tanggal) return "";

            const [tahun, bulan] = tanggal.split("-");
            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            bulanTahun = `${namaBulan[parseInt(bulan) - 1].toUpperCase()} ${tahun}`;
            $(".ket_tanggal").text(bulanTahun);
        }
    </script>
    <script>
        // untuk uang mmakan
        let table_rekap_uang_makan;

        const formatRupiah = value => "Rp " + value.toLocaleString("id-ID");

        $(document).ready(function() {
            // Inisialisasi kosong
            table_rekap_uang_makan = $('#table_rekap_uang_makan').DataTable({
                data: [],
                // responsive: true,
                autoWidth: true,
                columns: [{
                        title: "No",
                        data: null,
                        className: "text-center",
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                    },
                    {
                        data: 'NIP'
                    },
                    {
                        data: 'NAMA'
                    },
                    {
                        data: 'GOL/RUANG'
                    },
                    {
                        data: 'HARI MASUK'
                    },
                    {
                        data: 'UANG MAKAN',
                        render: data => formatRupiah(data)
                    },
                    {
                        data: 'PPH',
                        render: data => formatRupiah(data)
                    },
                    {
                        data: 'JUMLAH BERSIH',
                        render: data => formatRupiah(data)
                    },
                    {
                        data: 'STATUS PEGAWAI'
                    },
                    // {
                    //     data: 'SATKER_2'
                    // },
                    // {
                    //     data: 'SATKER_3'
                    // }
                ]
            });
        });

        function generateTableUangMakan(data) {
            let afterorder = urutkanPegawai(data);
            table_rekap_uang_makan.clear().rows.add(afterorder).draw();
        }
    </script>

    <script>
        //btn_export click
        $('#btn_export').click(function() {
            ExportToExcel()
        })
        $('#btn_exp_uang_makan').click(function() {
            Exportexceluangmakan()
        })
        $('#btn_exp_uangmakan_zip').click(function() {
            ExportZipUangMakan()
        })

        function Exportexceluangmakan() {
            if (hasilrekapuangmakan.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Data Kosong',
                })
                return
            }

            // return
            $.ajax({
                url: '/exportRekapUangMakan',
                type: 'POST',
                data: JSON.stringify({
                    data: hasilrekapuangmakan,
                    bulanTahun: bulanTahun,
                }),
                contentType: 'application/json',
                xhrFields: {
                    responseType: 'blob' // Penting untuk menerima file
                },
                success: function(response) {
                    // Buat link download
                    const url = window.URL.createObjectURL(response);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `Rekap_Uang_Makan_${bulanTahun}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    a.remove();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                }
            });
        }

        function startPolling() {
            let interval = setInterval(() => {
                $.get('/export/rekap/progress', function(res) {
                    let percent = (res.current / res.total) * 100;
                    $('#progress-bar').css('width', percent + '%');
                    $('#status').text(`📄 ${res.nama} (${res.current}/${res.total})`);

                    if (res.current >= res.total) {
                        clearInterval(interval);
                        $('#status').html(
                            `<a href="${res.download_url}" class="btn btn-success" download>
                                    ✅ Download ZIP 
                                </a>`
                        );

                        // Jika ada link download, tampilkan
                        if (res.download_url) {
                            // $('#download-link').html(
                            //     `<a href="${res.download_url}" target="_blank" class="btn btn-success mt-2">⬇️ Download ZIP</a>`
                            // );

                        }
                    }
                });
            }, 1000);
        }

        function ExportZipUangMakan() {
            if (hasilrekapuangmakan.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Data Kosong',
                });
                return;
            }
            $('#status').html('<span class="text-warning">🔄 Sedang diproses...</span>');
            // startPolling();
            // startPolling(); // ⬅️ MULAI POLLING DI SINI
            $.ajax({
                url: '/export/rekap-uang-makan',
                type: 'POST',
                data: JSON.stringify({
                    data: hasilrekapuangmakan,
                    bulanTahun: bulanTahun,
                }),
                contentType: 'application/json',
                success: function(res) {
                    if (res.status === 'started') {
                        $('#status').text('📤 Mulai proses ekspor...');

                    } else if (res.download_url) {
                        // fallback kalau tetap proses sinkron
                        $('#status').html(`
                            <a href="${res.download_url}" class="btn btn-success" download>
                                ✅ Download Rekap
                            </a>
                        `);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    $('#status').text('❌ Gagal memproses');
                }
            });




            function ExportToExcel() {
                if (untukExportRekap.length == 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Data Kosong',
                    })
                    return
                }

                // return
                $.ajax({
                    url: '/exportRekap',
                    type: 'POST',
                    data: JSON.stringify({
                        data: untukExportRekap,
                        bulanTahun: bulanTahun,
                        dataExportAbsensiSekali: dataExportAbsensiSekali,
                        dataExportTanpaKeterangan: dataExportTanpaKeterangan
                    }),
                    contentType: 'application/json',
                    xhrFields: {
                        responseType: 'blob' // Penting untuk menerima file
                    },
                    success: function(response) {
                        // Buat link download
                        const url = window.URL.createObjectURL(response);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'Rekap_Absensi.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        a.remove();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                        })
                    }
                });
            }


            function joinUangMakanDenganAbsensi(dataAbsensi, dataUangMakan) {
                const hasilGabungan = [];

                dataAbsensi.forEach(pegawai => {
                    const tanggalPertama = pegawai.detail?.[0]?.TANGGAL;

                    if (!tanggalPertama) {
                        console.warn(`Data absensi kosong untuk NIP ${pegawai.NIP}`);
                        return;
                    }

                    const tahunAbsen = new Date(tanggalPertama).getFullYear();
                    const bulanAbsen = new Date(tanggalPertama).getMonth() + 1;

                    // Cari data uang makan berdasarkan NIP
                    const uang = dataUangMakan.find(um => um.NIP === pegawai.NIP);

                    if (uang) {
                        const tahunUM = parseInt(uang.TAHUN);
                        const bulanUM = parseInt(uang.BULAN);

                        if (tahunUM !== tahunAbsen || bulanUM !== bulanAbsen) {
                            alert(
                                `❗ Perhatian: Data tahun/bulan tidak cocok untuk NIP ${pegawai.NIP} (${pegawai.NAMA})\nAbsensi: ${tahunAbsen}-${bulanAbsen}, Uang Makan: ${tahunUM}-${bulanUM}`
                            );
                        }

                        hasilGabungan.push({
                            ...pegawai,
                            "HARI MASUK": uang["HARI MASUK"] ?? null,
                            "UANG MAKAN": uang["UANG MAKAN"] ?? null,
                            "PPH": uang["PPH"] ?? null,
                            "JUMLAH BERSIH": uang["JUMLAH BERSIH"] ?? null
                        });
                    } else {
                        // Tidak ditemukan data uang makan
                        hasilGabungan.push({
                            ...pegawai,
                            "HARI MASUK": null,
                            "UANG MAKAN": null,
                            "PPH": null,
                            "JUMLAH BERSIH": null
                        });
                    }
                });

                return hasilGabungan;
            }

            function mappingDataAbsensi(dataAbsensi) {
                return dataAbsensi.map(pegawai => {
                    const nip = pegawai.NIP;
                    const nama = pegawai.NAMA;
                    const hariMasuk = pegawai["HARI MASUK"] || 0;
                    const jumlahKotor = pegawai["UANG MAKAN"] || 0;
                    const potonganPajak = pegawai["PPH"] || 0;
                    const totalBersih = pegawai["JUMLAH BERSIH"] || 0;

                    const uangPerHari = hariMasuk > 0 ? (jumlahKotor - potonganPajak) / hariMasuk : 0;

                    const detail = (pegawai.detail || []).map(item => {
                        const absenMasuk = item["ABSEN MASUK"]?.trim();
                        const absenPulang = item["ABSEN PULANG"]?.trim();

                        const adaAbsen = absenMasuk || absenPulang;
                        const uangHariIni = adaAbsen ? uangPerHari : null;

                        const keteranganGabungan = [
                                item.LIBUR?.trim(),
                                item.KETERANGAN?.trim(),
                                item["KETERANGAN 2"]?.trim()
                            ]
                            .filter(Boolean)
                            .join(" / ");

                        return {
                            tanggal: item.TANGGAL,
                            hari: item.HARI,
                            jam_masuk: item["JAM MASUK"],
                            absen_masuk: absenMasuk,
                            jam_pulang: item["JAM PULANG"],
                            absen_pulang: absenPulang,
                            uang_per_hari: uangHariIni,
                            keterangan: keteranganGabungan || "-"
                        };
                    });

                    return {
                        nip,
                        nama,
                        jumlah_kotor: jumlahKotor,
                        potongan_pajak: potonganPajak,
                        total_bersih: totalBersih,
                        detail
                    };
                });
            }
    </script>

    <script>
        function previewSatuPegawai() {

            $.ajax({
                url: '/generate-zip',
                type: 'POST',
                data: JSON.stringify({
                    pegawai_list: hasilrekapuangmakan, // array dari Laravel
                    bulanTahun: bulanTahun
                }),
                contentType: 'application/json',
                success: function(res) {
                    // window.location.href = res.download_url; // jika kamu buat link download balik
                },
                error: function(xhr) {
                    console.log(xhr);

                }
            });





            //         const pegawai = hasilrekapuangmakan[0]; // Ambil satu data pegawai dari JS variable kamu
            //         const bulanTahun = $('#bulanTahun').val(); // Atau dari variable JS langsung

            //         const form = document.createElement('form');
            //         form.method = 'POST';
            //         form.action = '/export/rekap-preview';
            //         form.target = '_blank';

            //         const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            //         form.innerHTML = `
        //     <input type="hidden" name="_token" value="${csrfToken}">
        //     <input type="hidden" name="data" value='${JSON.stringify([pegawai])}'>
        //     <input type="hidden" name="bulanTahun" value="${bulanTahun}">
        // `;

            //         document.body.appendChild(form);
            //         form.submit();
            //         form.remove();
        }
    </script>
@endsection
