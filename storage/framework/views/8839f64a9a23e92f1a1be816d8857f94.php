

<?php $__env->startSection('title', 'Membership Applications - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><?php echo e($club->name); ?> - Membership Applications</h2>
            <p class="text-muted">Review and approve/reject membership applications</p>
        </div>
    </div>

    <?php if($applications->count() > 0): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>University ID</th>
                                <th>Email</th>
                                <th>Applied On</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($member->name); ?></td>
                                    <td><?php echo e($member->university_id); ?></td>
                                    <td><?php echo e($member->email); ?></td>
                                    <td><?php echo e($member->pivot->created_at->diffForHumans()); ?></td>
                                    <td>
                                        <select id="position-<?php echo e($member->id); ?>" class="form-select form-select-sm" style="width: auto;">
                                            <option value="">Select Position</option>
                                            <option value="member">Member</option>
                                            <option value="secretary">Secretary</option>
                                            <option value="treasurer">Treasurer</option>
                                            <option value="vice_president">Vice President</option>
                                        </select>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?php echo e(route('clubs.member-status', [$club, $member])); ?>" style="display:inline;" class="approve-form">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="status" value="approved">
                                            <input type="hidden" name="position" class="position-input" value="">
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('clubs.member-status', [$club, $member])); ?>" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php echo e($applications->links()); ?>

            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    No pending applications at the moment.
                </div>
                <div class="text-center">
                    <a href="<?php echo e(route('clubs.show', $club)); ?>" class="btn btn-outline-primary">Back to Club</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Get the select element in the same row
            const selectElement = this.closest('tr').querySelector('select');
            const positionInput = this.querySelector('.position-input');
            positionInput.value = selectElement.value;
        });
    });
});
</script>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/clubs/applications.blade.php ENDPATH**/ ?>