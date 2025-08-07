<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="<?php echo e(asset('loginUI/css/style.css')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('templates/dist/img/favicon-32x32.png')); ?>">

</head>

<body class="img js-fullheight" style="background-image: url(<?php echo e(asset('loginUI/images/bg.jpg')); ?>);">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Ahlan Wa Sahlan</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h3 class="mb-4 text-center">Login Ma Ki</h3>
                        <?php echo $__env->make('pages.auth._message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <form action="" method="POST" class="signin-form">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?php echo e(asset('loginUI/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('loginUI/js/popper.js')); ?>"></script>
    <script src="<?php echo e(asset('loginUI/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('loginUI/js/main.js')); ?>"></script>

</body>

</html>
<?php /**PATH C:\laragon\www\SIREKAP\resources\views/pages/auth/login.blade.php ENDPATH**/ ?>