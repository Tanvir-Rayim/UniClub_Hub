

<?php $__env->startSection('title', 'Welcome - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <h1 class="display-4 fw-bold mb-4">Welcome to UniClub Hub</h1>
                <p class="lead text-muted mb-4">
                    Centralized University Club Management System
                </p>
                <p class="mb-5">
                    Manage your clubs, events, budgets, and memberships all in one place.
                </p>

                <?php if(auth()->guard()->guest()): ?>
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg px-4 gap-3">
                            Get Started - Register
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-secondary btn-lg px-4">
                            Already a member? Login
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary btn-lg">
                        Go to Dashboard
                    </a>
                <?php endif; ?>
            </div>

            <div class="row mt-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">📚 For Students</h5>
                            <p class="card-text">Join clubs, register for events, and connect with peers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">🎯 For Executives</h5>
                            <p class="card-text">Manage members, propose events, and handle budgets.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">👨‍💼 For Admins</h5>
                            <p class="card-text">Oversee clubs, manage approvals, and allocate venues.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/welcome.blade.php ENDPATH**/ ?>