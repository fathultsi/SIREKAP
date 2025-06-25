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

                <div id="rekapSection" class="d-none">
                    <h5>Ringkasan Rekapitulasi</h5>
                    <table class="table table-bordered" id="rekapTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Hari Hadir</th>
                                <th>Tanpa Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <h5>Detail Absensi Pegawai</h5>
                    <div id="detailList"></div>

                    <button id="exportBtn" class="btn btn-success mt-3">Download Rekap Excel</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="container mt-5">
                <h3 class="mb-4 text-center">Rekapitulasi Absensi Pegawai</h3>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-hover bg-white shadow-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Hari Masuk</th>
                                    <th>Telat/Cepat Pulang</th>
                                    <th>Absen Sekali</th>
                                    <th>Dinas</th>
                                    <th>Tanpa Keterangan</th>
                                    <th>Masuk Saat Libur</th>
                                </tr>
                            </thead>
                            <tbody id="rekap-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div
                                class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Rekap Absensi</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-cog"></i> Options
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#"><i
                                                class="fas fa-file-export mr-2"></i>Export</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-filter mr-2"></i>Filter</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="align-middle border-top-0">Nama</th>
                                                <th class="align-middle border-top-0">NIP</th>
                                                <th class="align-middle border-top-0 text-center">Hari Masuk</th>
                                                <th class="align-middle border-top-0 text-center">Telat/Cepat Pulang</th>
                                                <th class="align-middle border-top-0 text-center">Absen Sekali</th>
                                                <th class="align-middle border-top-0 text-center">Dinas</th>
                                                <th class="align-middle border-top-0 text-center">Tanpa Keterangan</th>
                                                <th class="align-middle border-top-0 text-center">Masuk Saat Libur</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rekap-body" class="bg-white">
                                            <!-- Table rows will be inserted here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Showing <span id="showing-count">1</span> to <span id="total-count">10</span> of <span
                                        id="total-entries">50</span> entries
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
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
                            <div id="modalContent" class="table-responsive">
                                <!-- Isi detail di sini -->
                                <div class="text-center my-4 text-muted" id="loadingSpinner">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Memuat...</span>
                                    </div>
                                    <p>Memuat detail...</p>
                                </div>
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
                dataPegawai = prosesDanGroupAbsensi(rows);
                console.log(dataPegawai);
                renderTabel(dataPegawai);
                // $("#output").text(JSON.stringify(rows, null, 2));
            };

            reader.readAsArrayBuffer(file);
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
                const isHariLibur = ["sabtu", "minggu"].includes(hari.toLowerCase()) || item.LIBUR?.trim() !== "";

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

                if (jenisTugas.includes("dinas")) {
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
        // Render Tabel Ringkasan
        function renderTabel(data) {
            const $body = $("#rekap-body");
            $body.empty();

            data.forEach((pegawai, index) => {
                const row = `
      <tr>
        <td>${pegawai.NAMA}</td>
        <td>${pegawai.NIP}</td>
        <td>${renderCount(pegawai.masukKerja, index, "masukKerja")}</td>
        <td>${renderCount(pegawai.terlambat, index, "terlambat")}</td>
        <td>${renderCount(pegawai.absenSekali, index, "absenSekali")}</td>
        <td>${renderCount(pegawai.dinas, index, "dinas")}</td>
        <td>${renderCount(pegawai.tanpaKeterangan, index, "tanpaKeterangan")}</td>
        <td>${renderCount(pegawai.masukSaatLibur, index, "masukSaatLibur")}</td>
      </tr>
    `;
                $body.append(row);
            });
        }

        // Fungsi untuk render jumlah dengan link klik jika > 0
        function renderCount(arr, index, kategori) {
            const count = arr.length;
            return count > 0 ?
                `<span class="clickable" onclick="showDetail(${index}, '${kategori}')">${count}</span>` :
                "0";
        }

        // Tampilkan Detail di Modal
        function showDetail(index, kategori) {
            const pegawai = dataPegawai[index];
            const data = pegawai[kategori];

            // Tampilkan loading spinner dulu
            $("#modalContent").html(`
    <div class="text-center my-4 text-muted">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Memuat...</span>
      </div>
      <p>Memuat detail...</p>
    </div>
  `);

            // Tampilkan modal terlebih dahulu
            $("#detailModal").modal("show");

            // Setelah modal terbuka, render isinya dengan delay kecil
            setTimeout(() => {
                let html = `<h5>${pegawai.NAMA} - ${kategori.replace(/([A-Z])/g, ' $1')}</h5>`;

                if (data.length === 0) {
                    html += `<p class="text-muted">Tidak ada data</p>`;
                } else {
                    html += `
      <table class="table table-sm table-bordered mt-3">
        <thead class="thead-light">
          <tr>
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
                                  <td>${row.TANGGAL?.split(" ")[0]}</td>
                                  <td>${row.HARI}</td>
                                  <td>${row["ABSEN MASUK"] || "-"}</td>
                                  <td>${row["ABSEN PULANG"] || "-"}</td>
                                  <td>${row["JENIS TUGAS"] || "-"}</td>
                                  <td>${row["KETERANGAN 2"] || "-"}</td>
                                </tr>
                              `).join('')}
        </tbody>
      </table>`;
                }

                $("#modalContent").html(html);
            }, 250); // Waktu tunggu minimal untuk UX yang smooth
        }


        // Inisialisasi
    </script>
@endsection
