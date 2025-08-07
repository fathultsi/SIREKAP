<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name')); ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('templates/plugins/fontawesome-free/css/all.min.css')); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="icon" type="image/png" href="<?php echo e(asset('templates/dist/img/favicon-32x32.png')); ?>">
    <!-- DataTables -->
    <?php echo $__env->yieldContent('DataTables'); ?>

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('templates/dist/css/adminlte.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('templates/plugins/toastr/toastr.min.css')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <style>
        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php echo $__env->make('layouts2.components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->

        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            

            <!-- Main content -->
            <section class="content">
                <?php echo $__env->yieldContent('content'); ?>

            </section>
            <!-- /.content -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button"
                aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright © 2025-<?php echo e(date('Y')); ?> <a href="#">SIREKAP versi
                    1.0</a>.</strong>

        </footer>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <?php echo $__env->yieldContent('modals'); ?>

    </div>
    <div id="loading" class="spinner-grow text-primary" role="status" style="display: none;">
        
        <span class="sr-only">Loading...</span>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?php echo e(asset('templates/plugins/jquery/jquery.min.js')); ?>"></script>
    <!-- Bootstrap 4 -->
    

    <!-- Bootstrap 4 -->
    <script src="<?php echo e(asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- AdminLTE App -->
    <!-- AdminLTE App -->
    <?php echo $__env->yieldContent('scripts_DataTables'); ?>
    <script src="<?php echo e(asset('templates/dist/js/adminlte.min.js')); ?>"></script>
    
    <script src="<?php echo e(asset('templates/plugins/toastr/toastr.min.js')); ?>"></script>

    <!-- AdminLTE for demo purposes -->
    


    <script>
        // $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Menampilkan spinner saat AJAX dimulai
        $(document).ajaxStart(function() {
            $('#loading').show(); // Tampilkan spinner
        });

        // Menyembunyikan spinner saat semua AJAX selesai
        $(document).ajaxStop(function() {
            $('#loading').hide(); // Sembunyikan spinner
        });

        // });

        //fomat number indonesia
        function formattedNumber(x) {
            let angka = parseFloat(x);
            return angka.toLocaleString("id-ID", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }


        // Ambil semua elemen dengan kelas "rupiah"
        const rupiahInputs = document.querySelectorAll(".rupiah");



        // Loop melalui setiap elemen dengan kelas "rupiah" dan tambahkan event listener
        rupiahInputs.forEach(input => {
            input.addEventListener("input", function(e) {
                // Hapus format sebelumnya, ambil hanya angka
                let cleanValue = e.target.value.replace(/[^0-9]/g, "");
                // Format dengan pemisah ribuan dan tampilkan kembali di input
                e.target.value = cleanValue ? formattedNumber(cleanValue) : "";
            });
        });


        function rupiahToFloat(rupiah) {
            // Hapus karakter yang bukan angka
            const cleanValue = rupiah.replace(/[^0-9]/g, "");

            // Ubah string menjadi float
            return parseFloat(cleanValue);
        }
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>


</body>

</html>
<?php /**PATH C:\laragon\www\SIREKAP\resources\views/layouts2/main.blade.php ENDPATH**/ ?>