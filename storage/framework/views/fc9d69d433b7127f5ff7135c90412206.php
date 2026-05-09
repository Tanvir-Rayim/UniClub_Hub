

<?php $__env->startSection('title', 'Edit Member - ' . $club->name . ' - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Member - <?php echo e($club->name); ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5><?php echo e($member->name); ?></h5>
                        <p class="text-muted"><?php echo e($member->university_id); ?> | <?php echo e($member->email); ?></p>
                    </div>

                    <form method="POST" action="<?php echo e(route('clubs.members.update', [$club, $member])); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-4">
                            <label for="position" class="form-label">Position</label>
                            <select id="position" name="position" class="form-select" required>
                                <option value="">Select Position</option>
                                <option value="member" <?php if($position === 'member'): echo 'selected'; endif; ?>>Member</option>
                                <option value="secretary" <?php if($position === 'secretary'): echo 'selected'; endif; ?>>Secretary</option>
                                <option value="treasurer" <?php if($position === 'treasurer'): echo 'selected'; endif; ?>>Treasurer</option>
                                <option value="vice_president" <?php if($position === 'vice_president'): echo 'selected'; endif; ?>>Vice President</option>
                            </select>
                            <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="<?php echo e(route('clubs.members', $club)); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/clubs/edit-member.blade.php ENDPATH**/ ?>