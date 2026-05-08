

<?php $__env->startSection('title', $event->title . ' - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0"><?php echo e($event->title); ?></h2>
            <p class="text-muted"><?php echo e($event->club->name); ?></p>
        </div>
        <div class="col-md-4 text-end">
            <?php if($event->status === 'draft'): ?>
                <a href="<?php echo e(route('events.edit', $event)); ?>" class="btn btn-warning">
                    Edit
                </a>
                <form action="<?php echo e(route('events.destroy', $event)); ?>" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this proposal?')">
                        Delete
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Club:</strong></td>
                            <td><?php echo e($event->club->name); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Venue:</strong></td>
                            <td>
                                <?php if($event->venue): ?>
                                    <?php echo e($event->venue->name); ?> (Capacity: <?php echo e($event->venue->capacity); ?>)
                                <?php else: ?>
                                    <span class="text-muted">Not selected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Proposed Date:</strong></td>
                            <td><?php echo e($event->proposed_date->format('F d, Y \a\t g:i A')); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Budget:</strong></td>
                            <td>
                                <?php if($event->budget): ?>
                                    $<?php echo e(number_format($event->budget, 2)); ?>

                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Expected Audience:</strong></td>
                            <td>
                                <?php if($event->expected_audience): ?>
                                    <?php echo e($event->expected_audience); ?> attendees
                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <?php if($event->status === 'pending_approval'): ?>
                                    <span class="badge bg-warning">Pending Approval</span>
                                <?php elseif($event->status === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php elseif($event->status === 'rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($event->status)); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Submitted By:</strong></td>
                            <td><?php echo e($event->creator->name); ?></td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="mb-3">Description</h6>
                    <p><?php echo e($event->description); ?></p>

                    <?php if($event->status === 'rejected' && $event->rejection_reason): ?>
                        <div class="alert alert-danger">
                            <h6>Rejection Reason:</h6>
                            <p class="mb-0"><?php echo e($event->rejection_reason); ?></p>
                        </div>
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
                    <a href="<?php echo e(route('events.budget.show', $event)); ?>" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-money-bill-wave me-1"></i> Manage Budget & Expenses
                    </a>
                    
                    <?php if($event->status === 'approved' && (auth()->user()->id === $event->created_by || auth()->user()->hasRole('admin'))): ?>
                        <a href="<?php echo e(route('attendance.show', $event)); ?>" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-users me-1"></i> Manage Attendance
                        </a>
                    <?php elseif($event->status === 'approved'): ?>
                        <form method="POST" action="<?php echo e(route('attendance.mark', $event)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php
                                $userAttendance = $event->attendances->where('user_id', auth()->id())->first();
                                $isMarked = $userAttendance && $userAttendance->marked_attended;
                            ?>
                            <?php if($isMarked): ?>
                                <button type="button" class="btn btn-success w-100 mb-2" disabled>
                                    <i class="fas fa-check me-1"></i> Attendance Marked
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-check-circle me-1"></i> Mark Attendance
                                </button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-primary w-100">
                        Back to My Proposals
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/events/show.blade.php ENDPATH**/ ?>