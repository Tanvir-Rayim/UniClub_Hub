

<?php $__env->startSection('title', 'Clubs - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fff;">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('clubs.index')); ?>" method="GET" class="row g-3">
                        <div class="col-lg-4 col-md-12">
                            <label class="form-label small fw-bold text-muted">Search Clubs</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-primary"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" 
                                       placeholder="Keyword (name, description...)" value="<?php echo e(request('search')); ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">Advisor</label>
                            <select name="advisor_id" class="form-select">
                                <option value="">All Advisors</option>
                                <?php $__currentLoopData = $advisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($advisor->id); ?>" <?php echo e(request('advisor_id') == $advisor->id ? 'selected' : ''); ?>>
                                        <?php echo e($advisor->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Recently Added</option>
                                <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Name (A-Z)</option>
                                <option value="popularity" <?php echo e(request('sort') == 'popularity' ? 'selected' : ''); ?>>Most Members</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                Filter
                            </button>
                            <?php if(request()->anyFilled(['search', 'advisor_id', 'sort'])): ?>
                                <a href="<?php echo e(route('clubs.index')); ?>" class="btn btn-outline-secondary shadow-sm">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
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