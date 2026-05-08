

<?php $__env->startSection('title', 'Student Dashboard - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-5">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold mb-1">Student Dashboard 🎓</h1>
                    <p class="text-muted fs-6">Welcome back, <?php echo e($user->name); ?>! Here is your campus life at a glance.</p>
                </div>
                <div class="text-end">
                    <a href="<?php echo e(route('clubs.index')); ?>" class="btn btn-primary btn-lg mb-2 shadow-sm">
                        <i class="fas fa-compass me-2"></i> Explore Clubs
                    </a>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #667eea;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-primary mb-2"><?php echo e($clubCount); ?></div>
                    <p class="text-muted mb-0 fw-500">Clubs Joined</p>
                    <small class="text-muted">Active memberships</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #f5576c;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-danger mb-2"><?php echo e($pendingApplications); ?></div>
                    <p class="text-muted mb-0 fw-500">Pending Applications</p>
                    <small class="text-muted">Awaiting club approval</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-bottom-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-users text-primary me-2"></i>Your Clubs
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($clubs->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('clubs.show', $club)); ?>" class="list-group-item list-group-item-action p-4 border-bottom border-light">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold text-dark"><?php echo e($club->name); ?></h6>
                                        <span class="badge bg-light text-secondary border">
                                            <i class="far fa-clock me-1"></i>
                                            Joined <?php echo e($club->pivot->joined_at ? $club->pivot->joined_at->diffForHumans() : 'Recently'); ?>

                                        </span>
                                    </div>
                                    <p class="mb-0 text-muted small"><?php echo e(Str::limit($club->description, 120)); ?></p>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="display-6 text-muted mb-3"><i class="fas fa-folder-open"></i></div>
                            <h5 class="text-muted">No Clubs Yet</h5>
                            <p class="text-muted mb-4">You haven't joined any clubs yet. Get involved on campus!</p>
                            <a href="<?php echo e(route('clubs.index')); ?>" class="btn btn-outline-primary px-4">
                                Browse Available Clubs
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-id-card text-secondary me-2"></i>Account Info
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block mb-1">University ID</small>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-secondary me-2"></i>
                                <span class="fw-500"><?php echo e($user->university_id); ?></span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block mb-1">Email Address</small>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-secondary me-2"></i>
                                <span class="fw-500"><?php echo e($user->email); ?></span>
                            </div>
                        </li>
                        <li>
                            <small class="text-muted d-block mb-1">Account Role</small>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-graduate text-secondary me-2"></i>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($user->role)); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('clubs.index')); ?>" class="btn btn-outline-primary text-start px-3 py-2">
                            <i class="fas fa-search me-2" style="width: 20px;"></i> Browse Clubs
                        </a>
                        <a href="<?php echo e(route('profile.show')); ?>" class="btn btn-outline-secondary text-start px-3 py-2">
                            <i class="fas fa-user me-2" style="width: 20px;"></i> View Profile
                        </a>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-secondary text-start px-3 py-2">
                            <i class="fas fa-user-edit me-2" style="width: 20px;"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .fw-500 {
        font-weight: 500;
    }
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .list-group-item-action {
        transition: background-color 0.2s ease-in-out;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        z-index: 1; /* Prevents border overlap issues on hover */
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/dashboards/student.blade.php ENDPATH**/ ?>