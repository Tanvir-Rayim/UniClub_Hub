

<?php $__env->startSection('title', 'Executive Dashboard - UniClub Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold mb-1">Welcome, <?php echo e($user->name); ?>! 👋</h1>
                    <p class="text-muted fs-6">Executive Dashboard - Manage your clubs and events</p>
                </div>
                <div class="text-end">
                    <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i> Create Event Proposal
                    </a>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #667eea;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-primary mb-2"><?php echo e($activeClubsCount); ?></div>
                    <p class="text-muted mb-0">Active Clubs</p>
                    <small class="text-muted">Under your management</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #764ba2;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-info mb-2"><?php echo e($totalMembers); ?></div>
                    <p class="text-muted mb-0">Total Members</p>
                    <small class="text-muted">Across all clubs</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #f5576c;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-danger mb-2"><?php echo e($proposedEventsCount); ?></div>
                    <p class="text-muted mb-0">Events</p>
                    <small class="text-muted">Pending or Approved</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #f093fb;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-success mb-2">
                        <?php
                            $approvedCount = $proposedEvents->where('status', 'approved')->count();
                        ?>
                        <?php echo e($approvedCount); ?>

                    </div>
                    <p class="text-muted mb-0">Approved</p>
                    <small class="text-muted">Ready to execute</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-primary w-100 py-2">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span class="d-block small">My Events</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('clubs.index')); ?>" class="btn btn-outline-info w-100 py-2">
                                <i class="fas fa-users me-2"></i>
                                <span class="d-block small">All Clubs</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('attendance.pending')); ?>" class="btn btn-outline-success w-100 py-2">
                                <i class="fas fa-check-circle me-2"></i>
                                <span class="d-block small">Attendance</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('profile.show')); ?>" class="btn btn-outline-warning w-100 py-2">
                                <i class="fas fa-user me-2"></i>
                                <span class="d-block small">Profile</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-secondary w-100 py-2">
                                <i class="fas fa-edit me-2"></i>
                                <span class="d-block small">Edit Profile</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('clubs.show', $clubs->first())); ?>" class="btn btn-outline-dark w-100 py-2" <?php if($clubs->count() === 0): ?> disabled <?php endif; ?>>
                                <i class="fas fa-cog me-2"></i>
                                <span class="d-block small">Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Clubs Management Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: #eff6ff; border-bottom: 2px solid #dbeafe;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-users me-2 text-primary"></i>Your Clubs
                    </h5>
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="input-group input-group-sm shadow-sm" style="min-width: 250px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-filter text-primary"></i></span>
                            <input type="text" id="clubSearch" class="form-control border-start-0" placeholder="Filter clubs by name...">
                        </div>
                        <span class="badge bg-primary rounded-pill px-3"><?php echo e($clubs->count()); ?></span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if($clubs->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Club Name</th>
                                        <th>Members</th>
                                        <th>Faculty Advisor</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <a href="<?php echo e(route('clubs.show', $club)); ?>" class="text-decoration-none fw-500">
                                                    <?php echo e($club->name); ?>

                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info rounded-pill"><?php echo e($club->getActiveMembersCount()); ?></span>
                                            </td>
                                            <td>
                                                <small><?php echo e($club->advisors?->name ?? 'Not Assigned'); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                                    <a href="<?php echo e(route('clubs.members', $club)); ?>" class="btn btn-sm btn-outline-primary" title="View Members">
                                                        <i class="fas fa-users"></i> Members
                                                    </a>
                                                    <a href="<?php echo e(route('clubs.applications', $club)); ?>" class="btn btn-sm btn-outline-warning" title="Applications">
                                                        <i class="fas fa-inbox"></i> Apps
                                                    </a>
                                                    <a href="<?php echo e(route('clubs.members.download-pdf', $club)); ?>" class="btn btn-sm btn-outline-success" title="Download PDF">
                                                        <i class="fas fa-download"></i> PDF
                                                    </a>
                                                    <a href="<?php echo e(route('clubs.show', $club)); ?>" class="btn btn-sm btn-outline-secondary" title="View Details">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">You are not assigned to any clubs yet.</p>
                            <a href="<?php echo e(route('clubs.index')); ?>" class="btn btn-primary">Browse Clubs</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Events Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #fff1f2; border-bottom: 2px solid #fecdd3;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-calendar-check me-2 text-danger"></i>Event Proposals
                    </h5>
                    <span class="badge bg-danger rounded-pill px-3"><?php echo e($proposedEventsCount); ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if($proposedEvents->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Event Title</th>
                                        <th>Club</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $proposedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <a href="<?php echo e(route('events.show', $event)); ?>" class="text-decoration-none fw-500">
                                                    <?php echo e($event->title); ?>

                                                </a>
                                            </td>
                                            <td>
                                                <small><?php echo e($event->club->name); ?></small>
                                            </td>
                                            <td>
                                                <small><?php echo e($event->proposed_date->format('M d, Y')); ?></small>
                                            </td>
                                            <td>
                                                <?php if($event->status === 'pending_approval'): ?>
                                                    <span class="badge bg-warning text-dark">⏳ Pending</span>
                                                <?php elseif($event->status === 'approved'): ?>
                                                    <span class="badge bg-success">✓ Approved</span>
                                                <?php elseif($event->status === 'rejected'): ?>
                                                    <span class="badge bg-danger">✗ Rejected</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e(ucfirst($event->status)); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-outline-info" title="View Event">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="<?php echo e(route('events.budget.show', $event)); ?>" class="btn btn-sm btn-outline-success" title="Budget & Expenses">
                                                        <i class="fas fa-money-bill-wave"></i> Budget
                                                    </a>
                                                    <?php if($event->status === 'approved'): ?>
                                                        <a href="<?php echo e(route('attendance.show', $event)); ?>" class="btn btn-sm btn-outline-primary" title="Manage Attendance">
                                                            <i class="fas fa-users-check"></i> Attendance
                                                        </a>
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
                            <i class="fas fa-calendar text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No event proposals yet.</p>
                            <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">Create Event Proposal</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar: Info & Quick Links -->
        <div class="col-lg-4">
            <!-- Account Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: #f0f9ff; border-bottom: 2px solid #bae6fd;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-user-circle me-2 text-info"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">Full Name</label>
                            <p class="fw-500 mb-0"><?php echo e($user->name); ?></p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">University ID</label>
                            <p class="fw-500 mb-0"><?php echo e($user->university_id); ?></p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">Email Address</label>
                            <p class="fw-500 mb-0"><?php echo e($user->email); ?></p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">Role</label>
                            <span class="badge bg-primary"><?php echo e(ucfirst($user->role)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Features -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: #fffbeb; border-bottom: 2px solid #fef3c7;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-star me-2 text-warning"></i>Key Features
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Member Management</small>
                                    <p class="small text-muted mb-0">Manage and track club members</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Event Planning</small>
                                    <p class="small text-muted mb-0">Create and manage events</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Budget Tracking</small>
                                    <p class="small text-muted mb-0">Monitor expenses and budgets</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Attendance Tracking</small>
                                    <p class="small text-muted mb-0">Track event attendance easily</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Real-time Reports</small>
                                    <p class="small text-muted mb-0">Download reports and analytics</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center py-4">
                    <i class="fas fa-life-ring text-primary mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2">Need Help?</h6>
                    <p class="small text-muted mb-3">Contact your faculty advisor or visit the help center for support.</p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="fas fa-question-circle me-1"></i> Get Help
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Help & Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Welcome to UniClub Hub! Here are some quick tips:</p>
                <ul>
                    <li><strong>Create Events:</strong> Use the "Create Event Proposal" button to submit new events</li>
                    <li><strong>Manage Members:</strong> Click "Members" in your club to manage membership</li>
                    <li><strong>Track Attendance:</strong> Use "Manage Attendance" for approved events</li>
                    <li><strong>Budget Management:</strong> Track expenses using the budget feature</li>
                    <li><strong>Download Reports:</strong> Export member lists as PDF</li>
                </ul>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-500 {
        font-weight: 500;
    }
    .stat-box-header {
        border-radius: 8px;
        overflow: hidden;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
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
            let clubNameElement = row.querySelector('td:first-child a');
            if (clubNameElement) {
                let clubName = clubNameElement.textContent.toLowerCase();
                if (clubName.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/dashboards/executive.blade.php ENDPATH**/ ?>