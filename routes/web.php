<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CheckinCheckoutController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::resource('/departments', DepartmentController::class);

Route::resource('/roles', RoleController::class);

Route::resource('/permissions', PermissionController::class);

Route::resource('/employee_profile', EmployeeProfileController::class)->only(['show', 'edit', 'update']);

Route::resource('/company_settings', CompanySettingController::class)->only(['show', 'edit', 'update']);

Route::get('/checkin-checkout', [CheckinCheckoutController::class, 'checkIncheckOut']);
Route::post('/checkin', [CheckinCheckoutController::class, 'checkIncheckoutHandler']);

Route::resource('/attendances', AttendanceController::class);

require __DIR__ . '/auth.php';
