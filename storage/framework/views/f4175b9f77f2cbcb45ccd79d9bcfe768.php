

<?php $__env->startSection('title', $club->name . ' - Members - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><?php echo e($club->name); ?> - Members</h2>
            <p class="text-muted">Manage club members and their positions</p>
        </div>
        <div class="col-md-4 text-end">
            <?php if($canEditMembers): ?>
                <a href="<?php echo e(route('clubs.members.download-pdf', $club)); ?>" class="btn btn-success me-2">
                    <i class="fas fa-download me-1"></i> Download PDF
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('clubs.show', $club)); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Club
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($members->count() > 0): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>University ID</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Joined</th>
                                <?php if($canEditMembers): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($member->name); ?></strong>
                                    </td>
                                    <td><?php echo e($member->university_id); ?></td>
                                    <td><?php echo e($member->email); ?></td>
                                    <td>
                                        <?php if($member->pivot->position): ?>
                                            <span class="badge bg-info">
                                                <?php echo e(ucwords(str_replace('_', ' ', $member->pivot->position))); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($member->pivot->joined_at): ?>
                                            <?php if(is_string($member->pivot->joined_at)): ?>
                                                <?php echo e(\Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y')); ?>

                                            <?php else: ?>
                                                <?php echo e($member->pivot->joined_at->format('M d, Y')); ?>

                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if($canEditMembers): ?>
                                        <td>
                                            <a href="<?php echo e(route('clubs.members.edit', [$club, $member])); ?>" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('clubs.members.remove', [$club, $member])); ?>" style="display:inline;" class="delete-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Remove" onclick="return confirm('Are you sure you want to remove this member?');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($members->links()); ?>

                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    No approved members yet.
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/clubs/members.blade.php ENDPATH**/ ?>