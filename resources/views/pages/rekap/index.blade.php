@extends('layouts.main')

@section('DataTables')
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
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
            <div class="card  w-100">
                <div class="card-header border-0">
                    <h3 class="card-title">Rekapitulasi Absensi Pegawai</h3>
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
                    // console.log("cekdata", cekdata);
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
          ${data.map(row => `
                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                        <td>${no_urut++}</td>
                                                                                                                                                                                                                                                                        <td>${row.TANGGAL || "-"}</td>
                                                                                                                                                                                                                                                                        <td>${row.HARI || "-"}</td>
                                                                                                                                                                                                                                                                        <td>${row["ABSEN MASUK"] || "-"}</td>
                                                                                                                                                                                                                                                                        <td>${row["ABSEN PULANG"] || "-"}</td>
                                                                                                                                                                                                                                                                        <td>${row["JENIS TUGAS"] || "-"}</td>
                                                                                                                                                                                                                                                                        <td>${row["KETERANGAN"] || row["KETERANGAN 2"]}</td>
                                                                                                                                                                                                                                                                    </tr>`).join('')} 
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
    </script>
@endsection
