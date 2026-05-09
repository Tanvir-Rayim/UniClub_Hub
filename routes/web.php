<?php

use App\Http\Controllers\AdvisorNotificationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubExecutiveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentEventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [ProfileController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [ProfileController::class, 'sendResetLink'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Authentication Required)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-based dashboards
    Route::get('/dashboard/student', [DashboardController::class, 'studentDashboard'])->name('student.dashboard')->middleware('role:student');
    Route::get('/dashboard/executive', [DashboardController::class, 'executiveDashboard'])->name('executive.dashboard')->middleware('role:executive,admin');
    Route::get('/dashboard/advisor', [DashboardController::class, 'advisorDashboard'])->name('advisor.dashboard')->middleware('role:advisor,admin');
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('role:admin');

    // Student Event Routes
    Route::middleware('role:student')->group(function () {
        Route::get('/events/calendar', [StudentEventController::class, 'calendar'])->name('student.events.calendar');
        Route::post('/events/{event}/register', [StudentEventController::class, 'register'])->name('student.events.register');
        Route::get('/my-tickets', [StudentEventController::class, 'myTickets'])->name('student.tickets');
        Route::post('/tickets/{registration}/cancel', [StudentEventController::class, 'cancel'])->name('student.tickets.cancel');
        
        // Event Feedback
        Route::post('/events/{event}/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('events.feedback.store');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('password.change');
    Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.update-role');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        
        Route::get('/venues', [VenueController::class, 'index'])->name('admin.venues.index');
        Route::post('/venues', [VenueController::class, 'store'])->name('admin.venues.store');
        Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
        Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
        Route::get('/clubs/{club}/edit', [ClubController::class, 'edit'])->name('clubs.edit');
        Route::put('/clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');
        
        // Admin Event Approval
        Route::post('/events/{event}/admin-approve', [EventController::class, 'adminApprove'])->name('events.admin-approve');
        Route::post('/events/{event}/admin-reject', [EventController::class, 'adminReject'])->name('events.admin-reject');
    });

    // Club Routes
    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');
    Route::post('/clubs/{club}/apply', [ClubController::class, 'applyForMembership'])->name('clubs.apply');

    // Executive routes
    Route::middleware('role:executive,admin')->group(function () {
        Route::get('/clubs/{club}/applications', [ClubController::class, 'membershipApplications'])->name('clubs.applications');
        Route::put('/clubs/{club}/member/{user}', [ClubController::class, 'approveMembership'])->name('clubs.member-status');
        Route::get('/clubs/{club}/members', [ClubController::class, 'members'])->name('clubs.members');
        Route::get('/clubs/{club}/members/{user}/edit', [ClubController::class, 'editMember'])->name('clubs.members.edit');
        Route::put('/clubs/{club}/members/{user}', [ClubController::class, 'updateMember'])->name('clubs.members.update');
        Route::delete('/clubs/{club}/members/{user}', [ClubController::class, 'removeMember'])->name('clubs.members.remove');
        Route::get('/clubs/{club}/members/download/pdf', [ClubController::class, 'downloadMembersPDF'])->name('clubs.members.download-pdf');
    });

    // Advisor routes
    Route::middleware('role:advisor,admin')->group(function () {
        Route::get('/event-notifications', [DashboardController::class, 'advisorEventNotifications'])->name('advisor.event-notifications');
        Route::get('/clubs/{club}/executives', [ClubExecutiveController::class, 'edit'])->name('clubs.executives.edit');
        Route::post('/clubs/{club}/executives/assign', [ClubExecutiveController::class, 'assign'])->name('clubs.executives.assign');
        Route::delete('/clubs/{club}/executives/{user}', [ClubExecutiveController::class, 'remove'])->name('clubs.executives.remove');
        Route::post('/events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
        Route::post('/events/{event}/reject', [EventController::class, 'reject'])->name('events.reject');
        
        // Advisor notifications
        Route::get('/advisor/notifications', [AdvisorNotificationController::class, 'index'])->name('advisor.notifications.index');
        Route::get('/advisor/notifications/pending', [AdvisorNotificationController::class, 'pending'])->name('advisor.notifications.pending');
        Route::post('/advisor/notifications/{notification}/mark-as-read', [AdvisorNotificationController::class, 'markAsRead'])->name('advisor.notifications.mark-read');
        Route::post('/advisor/notifications/mark-all-as-read', [AdvisorNotificationController::class, 'markAllAsRead'])->name('advisor.notifications.mark-all-read');
        Route::post('/advisor/notifications/{notification}/archive', [AdvisorNotificationController::class, 'archive'])->name('advisor.notifications.archive');
        Route::delete('/advisor/notifications/{notification}', [AdvisorNotificationController::class, 'destroy'])->name('advisor.notifications.destroy');
        
        // API endpoints
        Route::get('/api/advisor/notifications/unread-count', [AdvisorNotificationController::class, 'getUnreadCount'])->name('api.advisor.notifications.unread-count');
        Route::get('/api/advisor/notifications/recent/{limit?}', [AdvisorNotificationController::class, 'getRecent'])->name('api.advisor.notifications.recent');
    });

    // Executive Event Proposals
    Route::middleware('role:executive,admin')->group(function () {
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::get('/events/{event}/budget', [BudgetController::class, 'show'])->name('events.budget.show');
        Route::post('/events/{event}/budget-items', [BudgetController::class, 'storeBudgetItem'])->name('events.budget.store');
        Route::put('/events/{event}/budget-items/{budget}', [BudgetController::class, 'updateBudgetItem'])->name('events.budget.update');
        Route::delete('/events/{event}/budget-items/{budget}', [BudgetController::class, 'deleteBudgetItem'])->name('events.budget.delete');
        Route::post('/events/{event}/expense-proofs', [BudgetController::class, 'storeExpenseProof'])->name('events.expenses.store');
        Route::delete('/events/{event}/expense-proofs/{proof}', [BudgetController::class, 'deleteExpenseProof'])->name('events.expenses.delete');
        Route::get('/events/{event}/expense-proofs/{proof}/download', [BudgetController::class, 'downloadExpenseProof'])->name('events.expenses.download');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        
        // Attendance Routes
        Route::post('/events/{event}/attendance/notify', [AttendanceController::class, 'sendAttendanceNotification'])->name('attendance.notify');
        Route::get('/events/{event}/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
    });

    // Event Proposals (accessible by executives and advisors)
    Route::middleware('role:executive,advisor,admin')->group(function () {
        Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    });

    // Attendance marking (for all authenticated users)
    Route::middleware('auth')->group(function () {
        Route::post('/events/{event}/mark-attendance', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
        Route::get('/attendance/pending', [AttendanceController::class, 'pending'])->name('attendance.pending');
    });
});
