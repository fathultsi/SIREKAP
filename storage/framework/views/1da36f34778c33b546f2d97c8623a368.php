<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rekap Uang Makan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(public_path('templates/dist/uangmakan/bootstrap.min.css')); ?>">
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

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Rekap Kehadiran: <?php echo e($pegawai['nama']); ?> (<?php echo e($pegawai['nip']); ?>)</h4>
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
                                    <?php $__currentLoopData = $pegawai['detail']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $warning = !empty($d['keterangan']) && $d['keterangan'] !== '-';
                                            $danger = strtolower($d['keterangan']) === 'tanpa keterangan';
                                        ?>
                                        <tr class="<?php echo e($warning ? 'table-warning' : 'table-light'); ?>">
                                            <td class="<?php echo e($danger ? 'text-danger' : ''); ?>"><?php echo e($d['tanggal']); ?></td>
                                            <td class="<?php echo e($danger ? 'text-danger' : ''); ?>"><?php echo e($d['hari']); ?></td>
                                            <td><?php echo e($d['jam_masuk']); ?></td>
                                            <td><?php echo e($d['absen_masuk']); ?></td>
                                            <td><?php echo e($d['jam_pulang']); ?></td>
                                            <td><?php echo e($d['absen_pulang']); ?></td>
                                            <td><?php echo e($d['uang_per_hari']); ?></td>
                                            <td class="<?php echo e($danger ? 'text-danger' : ''); ?>">
                                                <?php if($warning): ?>
                                                    <b><?php echo e($d['keterangan']); ?></b>
                                                <?php else: ?>
                                                    <?php echo e($d['keterangan']); ?>

                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Jumlah Kotor</th>
                                        <th><?php echo e($pegawai['jumlah_kotor']); ?></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Potongan Pajak</th>
                                        <th><?php echo e($pegawai['potongan_pajak']); ?></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Total</th>
                                        <th><?php echo e($pegawai['total_bersih']); ?></th>
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
<?php /**PATH C:\laragon\www\SIREKAP\resources\views/pdf/rekap-uang-makan.blade.php ENDPATH**/ ?>