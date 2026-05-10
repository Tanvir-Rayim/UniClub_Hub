<?php $__env->startSection('title', 'Upcoming Events - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-5">

    
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold mb-1">📅 Upcoming Events</h1>
                    <p class="text-muted fs-6">Browse and register for upcoming club events across campus</p>
                </div>
                <a href="<?php echo e(route('student.tickets')); ?>" class="btn btn-outline-primary btn-lg shadow-sm">
                    <i class="fas fa-ticket-alt me-2"></i> My Tickets
                </a>
            </div>
            <hr class="my-4">
        </div>
    </div>

    
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 16px; background: #fff;">
        <div class="card-body p-4">
            <form action="<?php echo e(route('student.events.calendar')); ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-12">
                    <label class="form-label small fw-bold text-muted"><i class="fas fa-search me-1"></i> Search Events</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by title..." value="<?php echo e(request('search')); ?>" style="border-radius: 8px;">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-muted"><i class="fas fa-users me-1"></i> Club</label>
                    <select name="club_id" class="form-select" style="border-radius: 8px;">
                        <option value="">All Clubs</option>
                        <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($club->id); ?>" <?php echo e(request('club_id') == $club->id ? 'selected' : ''); ?>>
                                <?php echo e($club->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-muted"><i class="fas fa-calendar-alt me-1"></i> Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>" style="border-radius: 8px;">
                </div>
                <div class="col-lg-2 col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; padding: 0.6rem;">
                            Filter
                        </button>
                        <?php if(request()->anyFilled(['search', 'club_id', 'date'])): ?>
                            <a href="<?php echo e(route('student.events.calendar')); ?>" class="btn btn-outline-secondary" style="border-radius: 8px; padding: 0.6rem;">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <?php if($events->count() > 0): ?>
        <div class="row g-4">
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isRegistered  = in_array($event->id, $registeredEventIds);
                    $daysUntil     = now()->diffInDays($event->proposed_date, false);
                    $isThisWeek    = $daysUntil <= 7;
                    $isThisMonth   = $daysUntil <= 30;
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 event-card" style="border-radius: 16px; overflow: hidden;">

                        
                        <div style="height: 6px; background: linear-gradient(90deg, #667eea, #764ba2);"></div>

                        <div class="card-body p-4">
                            
                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                <?php if($isRegistered): ?>
                                    <span class="badge px-3 py-2" style="background:#d4edda; color:#155724; font-size:0.75rem;">
                                        <i class="fas fa-check me-1"></i>Registered
                                    </span>
                                <?php endif; ?>
                                <?php if($isThisWeek): ?>
                                    <span class="badge px-3 py-2" style="background:#fff3cd; color:#856404; font-size:0.75rem;">
                                        <i class="fas fa-fire me-1"></i>This Week
                                    </span>
                                <?php elseif($isThisMonth): ?>
                                    <span class="badge px-3 py-2" style="background:#d1ecf1; color:#0c5460; font-size:0.75rem;">
                                        <i class="fas fa-calendar me-1"></i>This Month
                                    </span>
                                <?php endif; ?>
                            </div>

                            
                            <h5 class="fw-bold mb-1" style="color:#2d3748;"><?php echo e($event->title); ?></h5>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-users me-1"></i><?php echo e($event->club->name); ?>

                            </p>

                            
                            <p class="text-muted small mb-4" style="line-height:1.6;">
                                <?php echo e(Str::limit($event->description, 100)); ?>

                            </p>

                            
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-calendar-day me-1"></i>Date</div>
                                        <div class="fw-semibold small"><?php echo e($event->proposed_date->format('M d, Y')); ?></div>
                                        <div class="text-muted" style="font-size:0.7rem;"><?php echo e($event->proposed_date->format('g:i A')); ?></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i>Venue</div>
                                        <div class="fw-semibold small">
                                            <?php echo e($event->venue ? $event->venue->name : 'TBA'); ?>

                                        </div>
                                    </div>
                                </div>
                                <?php if($event->expected_audience): ?>
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-chair me-1"></i>Capacity</div>
                                        <div class="fw-semibold small"><?php echo e(number_format($event->expected_audience)); ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-clock me-1"></i>In</div>
                                        <div class="fw-semibold small text-primary"><?php echo e($event->proposed_date->diffForHumans()); ?></div>
                                    </div>
                                </div>
                            </div>

                            
                            <?php if($isRegistered): ?>
                                <button class="btn w-100 py-2" disabled
                                    style="background:#d4edda; color:#155724; border-radius:10px; border:none; font-weight:600;">
                                    <i class="fas fa-check-circle me-2"></i>Already Registered
                                </button>
                            <?php else: ?>
                                <form action="<?php echo e(route('student.events.register', $event)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn w-100 py-2 join-btn"
                                        style="background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; border:none; border-radius:10px; font-weight:600; transition: all 0.2s;">
                                        <i class="fas fa-plus-circle me-2"></i>Join Event
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4" style="font-size:4rem;">🎭</div>
            <h4 class="text-muted fw-semibold">No Upcoming Events</h4>
            <p class="text-muted">Check back soon — club events will appear here once approved by advisors.</p>
            <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-outline-primary mt-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    <?php endif; ?>

    
    <?php if($events->count() > 0): ?>
    <div class="mt-5">
        <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
    .event-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(102, 126, 234, 0.18) !important;
    }
    .join-btn:hover {
        opacity: 0.88;
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/student/calendar.blade.php ENDPATH**/ ?>