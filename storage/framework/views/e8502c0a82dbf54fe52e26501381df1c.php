<?php if(!empty(session('success'))): ?>
    <div class="alert alert-success " role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
    </div>
<?php endif; ?>

<?php if(!empty(session('error'))): ?>
    <div class="alert alert-danger " role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\SIREKAP\resources\views/pages/auth/_message.blade.php ENDPATH**/ ?>