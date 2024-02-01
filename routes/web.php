<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MyPayrollController;
use App\Http\Controllers\MyProjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MyAttendanceController;
use App\Http\Controllers\AttendanceScanController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CheckinCheckoutController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/employees', EmployeeController::class);

Route::resource('/salaries', SalaryController::class);

Route::resource('/departments', DepartmentController::class);

Route::resource('/roles', RoleController::class);

Route::resource('/permissions', PermissionController::class);

Route::resource('/employee_profile', EmployeeProfileController::class)->only(['show', 'edit', 'update']);

Route::resource('/company_settings', CompanySettingController::class)->only(['show', 'edit', 'update']);

Route::get('/checkin-checkout', [CheckinCheckoutController::class, 'checkIncheckOut']);
Route::post('/checkin', [CheckinCheckoutController::class, 'checkIncheckoutHandler']);

Route::resource('/attendances', AttendanceController::class);
Route::get('/attendances_pdf_download', [AttendanceController::class, 'attendances_pdf_download'])->name('attendances_pdf_download');
Route::get('/attendances-overview', [AttendanceController::class, 'overview'])->name('attendances.overview');
Route::get('/attendances-overview-table', [AttendanceController::class, 'overview_table'])->name('attendances.overview_table');

Route::controller(PayrollController::class)->group(function () {
    Route::get('/payrolls', 'index')->name('payrolls.index');
    Route::get('/payrolls-table', 'payroll_table')->name('payrolls.payroll_table');
});

Route::controller(MyAttendanceController::class)->group(function () {
    Route::get('/my-attendances', 'index')->name('my_attendances.index');
    Route::get('/my-attendances-overview-table', 'my_overview_table')->name('my_attendances.my_overview_table');
});

Route::controller(MyPayrollController::class)->group(function () {
    Route::get('/my-payroll-table', 'my_payroll_table')->name('my_payroll.my_payroll_table');
});

Route::resource('attendance_scan', AttendanceScanController::class)->only(['index', 'store']);

Route::resource('my_projects', MyProjectController::class)->only(['index', 'show']);

Route::resource('projects', ProjectController::class);

Route::resource('/tasks', TaskController::class);
Route::get("/tasksRender", [TaskController::class, 'tasksRender']);
Route::get("/tasksDraggable", [TaskController::class, 'tasksDraggable']);

require __DIR__ . '/auth.php';
