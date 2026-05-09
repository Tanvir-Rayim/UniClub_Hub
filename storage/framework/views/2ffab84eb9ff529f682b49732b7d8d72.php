

<?php $__env->startSection('title', 'My Profile - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                                    <?php echo e(substr($user->name, 0, 1)); ?>

                                </div>
                                <h5 class="mt-3"><?php echo e($user->name); ?></h5>
                                <p class="text-muted"><?php echo e(ucfirst($user->role)); ?></p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td><?php echo e($user->name); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>University ID:</strong></td>
                                    <td><?php echo e($user->university_id); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email Address:</strong></td>
                                    <td><?php echo e($user->email); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone Number:</strong></td>
                                    <td><?php echo e($user->phone ?? 'Not provided'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Account Status:</strong></td>
                                    <td>
                                        <span class="badge bg-success"><?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Member Since:</strong></td>
                                    <td><?php echo e($user->created_at->format('M d, Y')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary w-100">
                                Edit Profile
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('password.change')); ?>" class="btn btn-outline-primary w-100">
                                Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/profile/show.blade.php ENDPATH**/ ?>