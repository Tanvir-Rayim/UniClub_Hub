<?php $__env->startSection('title', $isAdmin ? 'All Events - UniClub Hub' : 'My Event Proposals - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <?php if($isAdmin): ?>
                <h2 class="mb-0">All Events 📋</h2>
                <p class="text-muted">System-wide overview of all event proposals and approved events</p>
            <?php else: ?>
                <h2 class="mb-0">My Event Proposals</h2>
                <p class="text-muted">Manage your club event proposals</p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 text-end">
            <?php if(!$isAdmin): ?>
                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-success">
                    + Create New Proposal
                </a>
            <?php endif; ?>
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

    
    <div class="card shadow-sm mb-4 border-0" style="background: #f8fafc;">
        <div class="card-body">
            <form action="<?php echo e(route('events.index')); ?>" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="small text-muted mb-1">Search Event Title</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-primary"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="e.g. Workshop" value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">Filter by Club</label>
                    <select name="club_id" class="form-select">
                        <option value="">All Clubs</option>
                        <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($club->id); ?>" <?php echo e(request('club_id') == $club->id ? 'selected' : ''); ?>>
                                <?php echo e($club->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">Filter by Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <?php if(request()->anyFilled(['search', 'club_id', 'date'])): ?>
                        <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-secondary" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    
    <?php if($isAdmin): ?>
    <div class="row g-3 mb-4">
        <div class="col-auto">
            <span class="badge bg-warning text-dark fs-6 px-3 py-2 shadow-sm">
                ⏳ Pending: <?php echo e($events->where('status', 'pending_approval')->count()); ?>

            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-success fs-6 px-3 py-2 shadow-sm">
                ✅ Advisor Approved: <?php echo e($events->where('advisor_approval_status', 'approved')->count()); ?>

            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-danger fs-6 px-3 py-2 shadow-sm">
                ❌ Rejected: <?php echo e($events->where('status', 'rejected')->count()); ?>

            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-primary fs-6 px-3 py-2 shadow-sm">
                📅 Total Found: <?php echo e($events->count()); ?>

            </span>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <?php echo e($isAdmin ? 'All Event Proposals & Approved Events' : 'Event Proposals'); ?>

                        <span class="badge bg-info"><?php echo e($eventCount); ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($events->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Event Title</th>
                                        <th>Club</th>
                                        <th>Venue</th>
                                        <th>Proposed Date</th>
                                        <th>Budget</th>
                                        <?php if($isAdmin): ?>
                                            <th>Submitted By</th>
                                            <th>Advisor Decision</th>
                                        <?php endif; ?>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-3">
                                                <strong><?php echo e($event->title); ?></strong>
                                            </td>
                                            <td><?php echo e($event->club->name); ?></td>
                                            <td>
                                                <?php if($event->venue): ?>
                                                    <?php echo e($event->venue->name); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($event->proposed_date->format('M d, Y')); ?></td>
                                            <td>
                                                <?php if($event->budget): ?>
                                                    $<?php echo e(number_format($event->budget, 2)); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if($isAdmin): ?>
                                                <td>
                                                    <?php if($event->creator): ?>
                                                        <span class="badge bg-light text-dark border"><?php echo e($event->creator->name); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($event->advisor_approval_status === 'approved'): ?>
                                                        <span class="badge bg-success">✅ Approved</span>
                                                    <?php elseif($event->advisor_approval_status === 'rejected'): ?>
                                                        <span class="badge bg-danger">❌ Rejected</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">⏳ Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php if($event->status === 'pending_approval'): ?>
                                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                                <?php elseif($event->status === 'approved'): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php elseif($event->status === 'rejected'): ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e(ucfirst($event->status)); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <?php if(!$isAdmin): ?>
                                                        <a href="<?php echo e(route('events.budget.show', $event)); ?>" class="btn btn-sm btn-success" title="Budget">
                                                            <i class="fas fa-money-bill-wave"></i> Budget
                                                        </a>
                                                        <?php if($event->status === 'draft'): ?>
                                                            <a href="<?php echo e(route('events.edit', $event)); ?>" class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <?php if($isAdmin): ?>
                                <p class="text-muted mb-0">No events have been submitted yet.</p>
                            <?php else: ?>
                                <p class="text-muted mb-3">You haven't created any event proposals yet.</p>
                                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-success">
                                    Create Your First Proposal
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <?php if($isAdmin): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                    ← Back to Admin Dashboard
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('executive.dashboard')); ?>" class="btn btn-outline-secondary">
                    ← Back to Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/events/index.blade.php ENDPATH**/ ?>