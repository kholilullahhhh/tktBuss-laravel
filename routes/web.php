<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ============================================================
// AUTH
// ============================================================
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// LANDING PAGE (publik)
// ============================================================
Route::get('/', [LandingController::class, 'index']);

// ============================================================
// PENCARIAN TIKET (publik, pemesanan butuh login)
// ============================================================
Route::get('tiket', [TicketController::class, 'index'])->name('tickets.index');
Route::get('tiket/search', [TicketController::class, 'search'])->name('tickets.search');
Route::get('tiket/{schedule}', [TicketController::class, 'show'])->name('tickets.show');
Route::middleware('auth')->group(function () {
    Route::get('tiket/{schedule}/kursi', [TicketController::class, 'seats'])->name('tickets.seats');
    Route::post('tiket/{schedule}/booking', [TicketController::class, 'store'])->name('tickets.store');
});

// ============================================================
// WEBHOOK MIDTRANS (publik, CSRF exception)
// ============================================================
Route::post('payment/notification', [PaymentController::class, 'notification']);

// ============================================================
// CUSTOMER AREA (auth)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('customer/bookings', [CustomerController::class, 'bookings'])->name('customer.bookings');
    Route::get('customer/tickets', [CustomerController::class, 'tickets'])->name('customer.tickets');

    Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('booking/{booking}/pay', [PaymentController::class, 'pay'])->name('booking.pay');
    Route::get('booking/{booking}/tiket', [BookingController::class, 'ticket'])->name('booking.ticket');
    Route::get('booking/{booking}/tiket/pdf', [BookingController::class, 'downloadPdf'])->name('booking.ticket.pdf');

    // Personal Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// ============================================================
// ADMIN AREA (auth + role + permission)
// ============================================================
Route::middleware(['auth', 'role:super-admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard')->middleware('check.permission:admin.dashboard');

    // Master Data
    Route::resource('operators', OperatorController::class)->middleware('check.permission:admin.operators.index');
    Route::resource('buses', BusController::class)->middleware('check.permission:admin.buses.index');
    Route::post('buses/{bus}/generate-seats', [BusController::class, 'generateSeats'])->name('buses.generate-seats')->middleware('check.permission:admin.buses.index');
    Route::resource('seats', SeatController::class)->middleware('check.permission:admin.seats.index');
    Route::post('seats/generate', [SeatController::class, 'generate'])->name('seats.generate')->middleware('check.permission:admin.seats.index');
    Route::resource('terminals', TerminalController::class)->middleware('check.permission:admin.terminals.index');
    Route::resource('routes', RouteController::class)->middleware('check.permission:admin.routes.index');
    Route::resource('schedules', ScheduleController::class)->middleware('check.permission:admin.schedules.index');

    // Transaksi
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index')->middleware('check.permission:admin.bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show')->middleware('check.permission:admin.bookings.index');
    Route::put('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status')->middleware('check.permission:admin.bookings.index');

    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index')->middleware('check.permission:admin.payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show')->middleware('check.permission:admin.payments.index');
    Route::post('payments/{payment}/paid', [AdminPaymentController::class, 'markPaid'])->name('payments.mark-paid')->middleware('check.permission:admin.payments.index');
    Route::post('payments/{payment}/failed', [AdminPaymentController::class, 'markFailed'])->name('payments.mark-failed')->middleware('check.permission:admin.payments.index');

    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index')->middleware('check.permission:admin.customers.index');
    Route::get('customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show')->middleware('check.permission:admin.customers.index');

    // Laporan
    Route::get('reports/booking', [ReportController::class, 'booking'])->name('reports.booking')->middleware('check.permission:admin.reports.booking');
    Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue')->middleware('check.permission:admin.reports.revenue');
    Route::get('reports/travel', [ReportController::class, 'travel'])->name('reports.travel')->middleware('check.permission:admin.reports.travel');
    Route::get('reports/export/booking', [ReportController::class, 'exportBooking'])->name('reports.export-booking')->middleware('check.permission:admin.reports.booking');
    Route::get('reports/export/revenue', [ReportController::class, 'exportRevenue'])->name('reports.export-revenue')->middleware('check.permission:admin.reports.revenue');
    Route::get('reports/export/travel', [ReportController::class, 'exportTravel'])->name('reports.export-travel')->middleware('check.permission:admin.reports.travel');
    Route::get('reports/print/{type}', [ReportController::class, 'print'])->name('reports.print')->middleware('check.permission:admin.reports.booking');
});

// ============================================================
// ADMIN INFRASTRUKTUR (auth + role, nama route tanpa prefix admin.)
// ============================================================
Route::middleware(['auth', 'role:super-admin,admin'])->group(function () {
    Route::resource('admin/user', UserController::class)->names('user')->middleware('check.permission:user.index');
    Route::resource('admin/role', RoleController::class)->names('role')->middleware('check.permission:role.index');
    Route::resource('admin/menu', MenuController::class)->names('menu')->middleware('check.permission:menu.index');
    Route::get('admin/permission', [PermissionController::class, 'index'])->name('permission.index')->middleware('check.permission:permission.index');
    Route::put('admin/permission', [PermissionController::class, 'update'])->name('permission.update')->middleware('check.permission:permission.index');

    Route::get('admin/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('admin/activity-log/data', [ActivityLogController::class, 'getData'])->name('activity-log.data');
    Route::get('admin/activity-log/statistics', [ActivityLogController::class, 'statistics'])->name('activity-log.statistics');

    Route::get('admin/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('check.permission:settings.index');
    Route::post('admin/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('check.permission:settings.index');
    Route::get('admin/settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache')->middleware('check.permission:settings.index');

    Route::get('admin/impersonate/start/{id}', [ImpersonateController::class, 'start'])->name('impersonate.start');
    Route::get('admin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('impersonate.stop');

    Route::get('admin/system/health', [SystemController::class, 'health'])->name('system.health')->middleware('check.permission:system.health');
    Route::get('admin/system/backup', [SystemController::class, 'backup'])->name('system.backup')->middleware('check.permission:system.health');
});
