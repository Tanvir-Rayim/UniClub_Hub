

<?php $__env->startSection('title', 'Mark Attendance - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Mark Attendance</h2>
            <p class="text-muted">View and mark your attendance for upcoming club events</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $pendingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $userAttendance = $event->attendances->where('user_id', auth()->id())->first();
                $isMarked = $userAttendance && $userAttendance->marked_attended;
            ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <!-- Card Header with Status -->
                    <div class="card-header <?php if($isMarked): ?> bg-success text-white <?php else: ?> bg-light <?php endif; ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo e($event->title); ?></h5>
                            <?php if($isMarked): ?>
                                <span class="badge bg-white text-success">
                                    <i class="fas fa-check-circle"></i> Marked
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- Event Details -->
                        <div class="mb-3">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar"></i> Date:</strong>
                                <br>
                                <?php echo e($event->proposed_date->format('l, F d, Y')); ?>

                                <br>
                                <span class="text-muted small"><?php echo e($event->proposed_date->format('h:i A')); ?></span>
                            </p>

                            <p class="mb-2">
                                <strong><i class="fas fa-users"></i> Club:</strong>
                                <br>
                                <?php echo e($event->club->name); ?>

                            </p>

                            <?php if($event->venue): ?>
                                <p class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt"></i> Venue:</strong>
                                    <br>
                                    <?php echo e($event->venue->name); ?>

                                </p>
                            <?php endif; ?>

                            <?php if($event->description): ?>
                                <p class="mb-2">
                                    <strong><i class="fas fa-info-circle"></i> Description:</strong>
                                    <br>
                                    <small class="text-muted"><?php echo e(Str::limit($event->description, 100)); ?></small>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Attendance Status -->
                        <?php if($isMarked): ?>
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Attendance Marked</strong>
                                <br>
                                <small>You marked your attendance on <?php echo e($userAttendance->marked_at->format('M d, Y \a\t h:i A')); ?></small>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-bell me-2"></i>
                                <strong>Mark Your Attendance</strong>
                                <br>
                                <small>Please confirm your attendance by clicking the button below.</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Footer with Action Button -->
                    <div class="card-footer bg-white">
                        <?php if($isMarked): ?>
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check"></i> Already Marked
                            </button>
                        <?php else: ?>
                            <form method="POST" action="<?php echo e(route('attendance.mark', $event)); ?>" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check-circle"></i> Mark Attendance
                                </button>
                            </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-external-link-alt"></i> View Event Details
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-check" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">No Pending Attendance</h5>
                        <p class="text-muted">You don't have any pending attendance to mark right now.</p>
                        <a href="<?php echo e(route('clubs.show', ['club' => auth()->user()->clubs->first()])); ?>" class="btn btn-primary mt-3" <?php if(!auth()->user()->clubs->count()): ?> disabled <?php endif; ?>>
                            <i class="fas fa-arrow-left"></i> Back to Club
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($pendingEvents->hasPages()): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($pendingEvents->links()); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .stat-card {
        padding: 15px;
        border-radius: 8px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/attendance/pending.blade.php ENDPATH**/ ?>