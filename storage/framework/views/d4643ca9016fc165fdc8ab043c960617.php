

<?php $__env->startSection('title', $club->name . ' - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><?php echo e($club->name); ?></h2>
            <p class="text-muted"><?php echo e($club->description); ?></p>
        </div>
        <div class="col-md-4 text-end">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->hasRole('admin')): ?>
                    <a href="<?php echo e(route('clubs.edit', $club)); ?>" class="btn btn-warning">Edit</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">About This Club</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Active Members:</strong></td>
                            <td><span class="badge bg-info"><?php echo e($club->getActiveMembersCount()); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Pending Applications:</strong></td>
                            <td><span class="badge bg-warning"><?php echo e($club->getPendingMembersCount()); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Faculty Advisor:</strong></td>
                            <td><?php echo e($club->advisors?->name ?? 'Not Assigned'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created By:</strong></td>
                            <td><?php echo e($club->creator?->name); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-<?php echo e($club->is_active ? 'success' : 'danger'); ?>">
                                    <?php echo e($club->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Members</h5>
                </div>
                <div class="card-body">
                    <?php if($club->members->count() > 0): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $club->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-1"><?php echo e($member->name); ?></h6>
                                            <p class="mb-0 text-muted small"><?php echo e($member->university_id); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge bg-<?php echo e($member->pivot->status == 'approved' ? 'success' : 'warning'); ?>">
                                                <?php echo e(ucfirst($member->pivot->status)); ?>

                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if(auth()->guard()->check()): ?>
                                                <?php if(Auth::user()->id == $club->created_by && $member->pivot->status == 'pending'): ?>
                                                    <form method="POST" action="<?php echo e(route('clubs.member-status', [$club, $member])); ?>" style="display:inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No members yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->isStudent()): ?>
                            <?php if(!Auth::user()->clubs->contains($club->id)): ?>
                                <form method="POST" action="<?php echo e(route('clubs.apply', $club)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary w-100">
                                        Apply for Membership
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <small>You are already a member of this club.</small>
                                </div>
                            <?php endif; ?>
                        <?php elseif(Auth::user()->isExecutive()): ?>
                            <div class="alert alert-info">
                                <small>Executives are assigned to clubs by advisors, not through membership applications.</small>
                            </div>
                        <?php endif; ?>

                        <?php if(Auth::user()->id == $club->created_by || Auth::user()->hasRole('admin')): ?>
                            <a href="<?php echo e(route('clubs.applications', $club)); ?>" class="btn btn-secondary w-100">
                                View Applications
                            </a>
                            <a href="<?php echo e(route('clubs.members', $club)); ?>" class="btn btn-info w-100 mt-2">
                                Manage Members
                            </a>
                        <?php elseif(Auth::user()->isExecutive() && Auth::user()->executives->contains('club_id', $club->id)): ?>
                            <a href="<?php echo e(route('clubs.members', $club)); ?>" class="btn btn-info w-100">
                                View & Manage Members
                            </a>
                        <?php elseif(Auth::user()->isAdvisor() && $club->faculty_advisor_id == Auth::user()->id): ?>
                            <a href="<?php echo e(route('clubs.members', $club)); ?>" class="btn btn-info w-100">
                                View & Manage Members
                            </a>
                        <?php endif; ?>

                        <?php if(Auth::user()->isAdvisor() && $club->faculty_advisor_id == Auth::user()->id || Auth::user()->hasRole('admin')): ?>
                            <a href="<?php echo e(route('clubs.executives.edit', $club)); ?>" class="btn btn-info w-100 mt-2">
                                Manage Executives
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">
                            <a href="<?php echo e(route('login')); ?>">Login</a> to join this club
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->hasRole('admin')): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Admin Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="<?php echo e(route('clubs.edit', $club)); ?>" class="btn btn-warning w-100 mb-2">
                                Edit Club
                            </a>
                            <form method="POST" action="<?php echo e(route('clubs.update', $club)); ?>" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="name" value="<?php echo e($club->name); ?>">
                                <button type="submit" class="btn btn-danger w-100">
                                    Deactivate Club
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/clubs/show.blade.php ENDPATH**/ ?>