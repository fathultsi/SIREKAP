<!-- Modal -->

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Absensi</h3>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="modalContent" class="table-responsive"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_hari_libur" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Hari Libur</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped  w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_hari_libur">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_duplikat" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal_title_duplikat">Detail Duplikat</h3>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped  w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="w-20">Nama</th>
                            <th>NIP</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Absen Masuk</th>
                            <th>Absen Pulang</th>
                            <th>Jenis Tugas</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_duplikat">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_absen_libur" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Absensi Saat Libur</h3>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped  w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="w-20">Nama</th>
                            <th>NIP</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Absen Masuk</th>
                            <th>Absen Pulang</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_absen_libur">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SIREKAP\resources\views/pages/rekap/modals.blade.php ENDPATH**/ ?>