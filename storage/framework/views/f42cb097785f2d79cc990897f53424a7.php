

<?php $__env->startSection('title', 'Advisor Dashboard - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-5">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold mb-1">Advisor Dashboard 👋</h1>
                    <p class="text-muted fs-6">Welcome back, <?php echo e($user->name); ?>! Review and manage club activities.</p>
                </div>
                <div class="text-end">
                    <a href="<?php echo e(route('advisor.notifications.pending')); ?>" class="btn btn-warning btn-lg mb-2 shadow-sm">
                        <i class="fas fa-bell me-2"></i> View All Notifications
                    </a>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #667eea;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-primary mb-2"><?php echo e($clubCount); ?></div>
                    <p class="text-muted mb-0">Clubs Advised</p>
                    <small class="text-muted">Under your supervision</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #ffc107;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-warning mb-2"><?php echo e($pendingEventsCount); ?></div>
                    <p class="text-muted mb-0">Pending Proposals</p>
                    <small class="text-muted">Awaiting your approval</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #28a745;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-success mb-2"><?php echo e($pendingEvents->count()); ?></div>
                    <p class="text-muted mb-0">Total Events</p>
                    <small class="text-muted">From supervised clubs</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #17a2b8;">
                <div class="card-body text-center">
                    <?php
                        $unreadNotifications = \App\Models\AdvisorEventNotification::forAdvisor($user->id)->pending()->count();
                    ?>
                    <div class="display-5 fw-bold text-info mb-2"><?php echo e($unreadNotifications); ?></div>
                    <p class="text-muted mb-0">Unread Alerts</p>
                    <small class="text-muted">Event notifications</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #fffbeb; border-bottom: 2px solid #fef3c7;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-calendar-check me-2 text-warning"></i>Event Proposals
                    </h5>
                    <?php if($pendingEventsCount > 0): ?>
                        <span class="badge bg-warning text-dark rounded-pill px-3"><?php echo e($pendingEventsCount); ?> Pending</span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if($pendingEvents->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Event Title</th>
                                        <th>Club</th>
                                        <th>Proposed Date</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $pendingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <strong><?php echo e($event->title); ?></strong>
                                                <div class="small text-muted">By <?php echo e($event->creator->name); ?></div>
                                            </td>
                                            <td><?php echo e($event->club->name); ?></td>
                                            <td><?php echo e($event->proposed_date->format('M d, Y')); ?></td>
                                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-outline-info" title="View Details">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <form action="<?php echo e(route('events.approve', $event)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($event->id); ?>" title="Reject">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="rejectModal<?php echo e($event->id); ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-danger text-white border-bottom-0">
                                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Reject Proposal</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('events.reject', $event)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-body p-4">
                                                            <p>Are you sure you want to reject <strong><?php echo e($event->title); ?></strong>?</p>
                                                            <div class="mb-3">
                                                                <label for="rejection_reason" class="form-label fw-500">Reason for Rejection (Optional)</label>
                                                                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" placeholder="Provide feedback to the club..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light border-top-0">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="display-6 text-muted mb-3"><i class="fas fa-check-circle"></i></div>
                            <h5 class="text-muted">All caught up!</h5>
                            <p class="text-muted mb-0">No pending event proposals at this time.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #ecfdf5; border-bottom: 2px solid #d1fae5;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-money-bill-wave me-2 text-success"></i>Financial Releases
                    </h5>
                    <?php if($approvedEventsCount > 0): ?>
                        <span class="badge bg-success rounded-pill px-3"><?php echo e($approvedEventsCount); ?> Action Required</span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if($approvedEvents->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Event</th>
                                        <th>Club</th>
                                        <th>Budget</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $approvedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <strong><?php echo e($event->title); ?></strong>
                                                <div class="small text-muted"><?php echo e($event->proposed_date->format('M d, Y')); ?></div>
                                            </td>
                                            <td><?php echo e($event->club->name); ?></td>
                                            <td>$<?php echo e(number_format($event->budget, 2)); ?></td>
                                            <td class="text-end pe-4">
                                                <form action="<?php echo e(route('events.budget.release', $event)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-hand-holding-usd me-1"></i> Release Funds
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No approved events awaiting financial release.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: #eff6ff; border-bottom: 2px solid #dbeafe;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-users me-2 text-primary"></i>Clubs Under Your Supervision
                    </h5>
                    <div class="ms-auto" style="min-width: 250px;">
                        <div class="input-group input-group-sm shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-filter text-primary"></i></span>
                            <input type="text" id="clubSearch" class="form-control border-start-0" placeholder="Filter clubs by name...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if($clubs->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Club Name</th>
                                        <th>Club Executive</th>
                                        <th>Members</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <strong><?php echo e($club->name); ?></strong>
                                                <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                    <?php echo e($club->description); ?>

                                                </div>
                                            </td>
                                            <td><?php echo e($club->creator->name); ?></td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill px-3">
                                                    <?php echo e($club->getActiveMembersCount()); ?>

                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="<?php echo e(route('clubs.show', $club)); ?>" class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">You are not advising any clubs yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            
            <?php
                // Note: Consider moving this DB query to your Controller
                $pendingNotifications = \App\Models\AdvisorEventNotification::forAdvisor($user->id)->pending()->latest()->take(5)->get();
            ?>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #fff7ed; border-bottom: 2px solid #ffedd5;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-bell text-warning me-2"></i> Recent Alerts
                    </h5>
                    <?php if($pendingNotifications->count() > 0): ?>
                        <span class="badge bg-warning text-dark"><?php echo e($pendingNotifications->count()); ?> New</span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if($pendingNotifications->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $pendingNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item list-group-item-action p-3 border-bottom border-light">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <strong class="text-truncate me-2"><?php echo e($notification->event->title); ?></strong>
                                        <small class="text-muted text-nowrap"><?php echo e($notification->created_at->diffForHumans(null, true, true)); ?></small>
                                    </div>
                                    <p class="mb-2 small text-muted">
                                        <i class="fas fa-users-cog me-1"></i> <?php echo e($notification->club->name); ?>

                                    </p>
                                    <a href="<?php echo e(route('events.show', $notification->event)); ?>" class="btn btn-sm btn-warning w-100 fw-500">
                                        Review Proposal
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($unreadNotifications > $pendingNotifications->count()): ?>
                            <div class="card-footer bg-white text-center border-top-0 pt-3">
                                <a href="<?php echo e(route('advisor.notifications.pending')); ?>" class="text-warning text-decoration-none fw-500 small">
                                    View all <?php echo e($unreadNotifications); ?> pending proposals <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-double text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0">No new alerts to display.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-secondary"></i>Advisor Resources</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary"><i class="fas fa-chevron-right me-2 text-muted" style="font-size: 0.75rem;"></i>Event Approval Guidelines</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary"><i class="fas fa-chevron-right me-2 text-muted" style="font-size: 0.75rem;"></i>University Club Policies</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary"><i class="fas fa-chevron-right me-2 text-muted" style="font-size: 0.75rem;"></i>Contact Administration</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .fw-500 {
        font-weight: 500;
    }
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    /* Disable hover effect on structural/sidebar cards if desired */
    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
<?php $__env->startSection('scripts'); ?>
<script>
    document.getElementById('clubSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('table tbody tr');
        
        rows.forEach(row => {
            let clubName = row.querySelector('td:first-child strong').textContent.toLowerCase();
            if (clubName.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/dashboards/advisor.blade.php ENDPATH**/ ?>