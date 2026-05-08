

<?php $__env->startSection('title', 'Clubs - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Available Clubs</h2>
        </div>
        <div class="col-md-4 text-end">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->hasRole('admin')): ?>
                    <a href="<?php echo e(route('clubs.create')); ?>" class="btn btn-primary">
                        + Create New Club
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($club->name); ?></h5>
                        <p class="card-text text-muted"><?php echo e(Str::limit($club->description, 100)); ?></p>
                        
                        <div class="mb-3">
                            <span class="badge bg-info"><?php echo e($club->getActiveMembersCount()); ?> members</span>
                            <?php if($club->advisors): ?>
                                <span class="badge bg-secondary"><?php echo e($club->advisors->name); ?></span>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo e(route('clubs.show', $club)); ?>" class="btn btn-sm btn-primary w-100">
                            View Details
                        </a>
                    </div>
                    <div class="card-footer bg-light">
                        <small class="text-muted">Created <?php echo e($club->created_at->diffForHumans()); ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    No clubs available at the moment.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <?php echo e($clubs->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/clubs/index.blade.php ENDPATH**/ ?>