

<?php $__env->startSection('title', $event->title . ' - Attendance - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><?php echo e($event->title); ?> - Attendance</h2>
            <p class="text-muted">Track member attendance for this event</p>
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
            <!-- Attendance Overview -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Total Members</h6>
                                <h3 class="text-primary"><?php echo e($totalCount); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Present</h6>
                                <h3 class="text-success"><?php echo e($attendedCount); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Absent</h6>
                                <h3 class="text-danger"><?php echo e($totalCount - $attendedCount); ?></h3>
                            </div>
                        </div>
                    </div>
                    <?php if($totalCount > 0): ?>
                        <div class="mt-3">
                            <div class="progress" style="height: 25px;">
                                <?php
                                    $percentage = ($attendedCount / $totalCount) * 100;
                                ?>
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($percentage); ?>%;" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo e(number_format($percentage, 1)); ?>%
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Attendance Notification Status -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notification Status</h5>
                    <?php if(!$notificationSent): ?>
                        <form method="POST" action="<?php echo e(route('attendance.notify', $event)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-bell me-1"></i> Send Attendance Notification
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if($notificationSent): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Notification Sent</strong>
                            <?php if($lastNotification): ?>
                                <br><small class="text-muted">Sent on <?php echo e($lastNotification->sent_at->format('M d, Y \a\t h:i A')); ?> by <?php echo e($lastNotification->sentBy->name); ?></small>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted">All club members have been notified to mark their attendance.</p>
                    <?php else: ?>
                        <p class="text-muted">Click the button above to send an attendance notification to all club members.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Member Attendance List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Member Attendance</h5>
                </div>
                <div class="card-body">
                    <?php if($attendances->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member Name</th>
                                        <th>University ID</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Marked At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($attendance->user->name); ?></strong>
                                            </td>
                                            <td><?php echo e($attendance->user->university_id); ?></td>
                                            <td><?php echo e($attendance->user->email); ?></td>
                                            <td>
                                                <?php if($attendance->marked_attended): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Present
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times"></i> Not Marked
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($attendance->marked_at): ?>
                                                    <?php echo e($attendance->marked_at->format('M d, Y h:i A')); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($attendances->links()); ?>

                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No attendance records yet. Send a notification first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Info</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Club:</strong> <?php echo e($event->club->name); ?></p>
                    <p><strong>Date:</strong> <?php echo e($event->proposed_date->format('M d, Y h:i A')); ?></p>
                    <p><strong>Expected Audience:</strong> <?php echo e($event->expected_audience ?? 'Not specified'); ?></p>
                    <p><strong>Status:</strong>
                        <?php if($event->status === 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/attendance/show.blade.php ENDPATH**/ ?>