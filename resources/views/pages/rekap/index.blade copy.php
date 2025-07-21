@extends('layouts.main')

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



        .db-attendance-detail {
            display: flex;
            gap: 8px;
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">Upload Absensi Excel</h3>
                <input type="file" id="excelFile" accept=".xlsx" class="form-control mb-3">


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
                        <h2 class="db-card-value">3</h2>
                        <p class="db-card-desc">hari bulan <span class="ket_tanggal"></span></p>
                    </div>

                    {{-- <div class="db-card-footer">
                        <a href="#" class="db-card-link">Tinjau Data <i class="fas fa-chevron-right"></i></a>
                    </div> --}}
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
                        <h2 class="db-card-value">14</h2>
                        <p class="db-card-desc">entri terdeteksi</p>
                    </div>
                    <div class="db-card-footer">
                        <a href="#" class="db-card-link">Tinjau Data <i class="fas fa-chevron-right"></i></a>
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
                        <h3 class="db-card-title">Masuk Saat Libur</h3>
                    </div>
                    <div class="db-card-body">
                        <h2 class="db-card-value">0</h2>
                        <p class="db-card-desc">Pegawai</p>
                    </div>
                    <div class="db-card-footer">
                        <div class="db-attendance-detail">
                            <span class="db-badge db-dark">5 OT</span>
                            <span class="db-badge db-light">3 Regular</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="card  w-100">
                <div class="card-header border-0">
                    <h3 class="card-title">Rekapitulasi Absensi Pegawai <span class="ket_tanggal"></span></h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm bg-info">
                            <i class="fas fa-download"></i>
                            <span>Export</span>
                        </a>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="rekapTable" class="table table-bordered table-hover bg-white shadow-sm">
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
                                        <th>Masuk Saat Libur</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Absensi</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div id="modalContent" class="table-responsive"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
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
        const nip6HariKerja = [
            "196812312005011067",
            "197002102006042001",
            "198007132014101003"
        ];

        $(document).ready(function() {
            $('#rekapTable').DataTable();

            $("#excelFile").on("change", function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: "array"
                    });

                    // Sheet pertama
                    const sheetName = workbook.SheetNames[0];
                    const sheet = workbook.Sheets[sheetName];

                    // Konversi ke array of objects
                    const jsonData = XLSX.utils.sheet_to_json(sheet, {
                        header: 1, // Pakai header:1 untuk debugging
                        defval: "", // Agar sel kosong tetap muncul
                    });

                    // Jika kamu ingin parsing ke object dengan key dari baris pertama:
                    const headers = jsonData[0];
                    const rows = jsonData.slice(1).map(row => {
                        let obj = {};
                        headers.forEach((key, i) => {
                            obj[key] = row[i];
                        });
                        return obj;
                    });

                    console.log(rows);

                    const cekduplikat = hapusDuplikatAbsensi(rows);
                    const rekapkerja_libur = rekapHariKerjaDanLibur(cekduplikat
                        .unique); //untuk total hari kerja dan libur
                    console.log("rekapkerja_libur", rekapkerja_libur);
                    formatBulanTahunDariData(cekduplikat.unique);

                    dataPegawai = prosesDanGroupAbsensi(cekduplikat.unique);
                    dataPegawai = urutkanPegawai(dataPegawai);
                    inisialisasiRekap(dataPegawai);
                    // $("#output").text(JSON.stringify(rows, null, 2));
                };

                reader.readAsArrayBuffer(file);
            });



            $('#rekapTable tbody').on('click', '.clickable', function() {
                const index = $(this).data('index');
                const key = $(this).data('key');
                tampilkanDetail(index, key, dataPegawai);
            });

        });

        function prosesDanGroupAbsensi(data) {
            const grouped = {};

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
                    if (adaAbsen) grouped[key].masukSaatLibur.push(item);
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
                if (isAbsenSekali) grouped[key].absenSekali.push(item);
                if (isTanpaKeterangan) grouped[key].tanpaKeterangan.push(item);
            });

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
            let newData = [];
            console.log(data);
            const table = $('#rekapTable').DataTable();
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
                    NAMA: pegawai.NAMA,
                    NIP: pegawai.NIP,
                    H_MASUK: pegawai.masukKerja.length,
                    TELAT_CEPAT_PULANG: pegawai.terlambat.length,
                    ABSEN_SEKALI: pegawai.absenSekali.length,
                    DINAS: pegawai.dinas.length,
                    TANPA_KETERANGAN: pegawai.tanpaKeterangan.length,
                    MASUK_SAAT_LIBUR: pegawai.masukSaatLibur.length
                });
            });
            console.log("data", newData);
            table.draw();
        }

        function renderClickable(array, index, key) {
            const jumlah = array.length;
            return jumlah > 0 ?
                `<span class="clickable" data-index="${index}" data-key="${key}">${jumlah}</span>` :
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

        function hapusDuplikatAbsensi(rows) {
            const seen = new Set();
            const unique = [];
            const duplikat = [];

            rows.forEach(row => {
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

            // return {
            //     liburResmi,
            //     totalHariKerja5,
            //     totalHariKerja6,
            //     totalLibur
            // };
            //hari_kerja5
            $("#hari_kerja5").text(`${totalHariKerja5} Hari`);
            //hari_kerja6
            $("#hari_kerja6").text(`${totalHariKerja6} Hari`);
        }

        function formatBulanTahunDariData(rows) {
            if (!rows.length) return "";

            const tanggal = rows[0].TANGGAL?.split(" ")[0]; // Ambil bagian '2025-06-01'
            if (!tanggal) return "";

            const [tahun, bulan] = tanggal.split("-");
            const namaBulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            $(".ket_tanggal").text(`${namaBulan[parseInt(bulan) - 1].toUpperCase()} ${tahun}`);
        }
    </script>
@endsection
