<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Middleware\RoleRedirect;


// 管理者ログイン関連
Route::prefix('admin')->group(function () {
    Route::get('/login', fn () => view('auth.admin_login'))->name('admin.login');
    Route::post('/login', [\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'store'])->name('admin.login.post');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // 申請一覧のルート（認証ミドルウェアで区別）
    Route::get('/stamp_correction_request/list', [AttendanceController::class, 'stamp_list'])->middleware(RoleRedirect::class)->name('stamp.list');
    // 勤怠詳細のルート
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'attendance_detail'])->name('attendance.detail');

    // 管理者専用
    Route::middleware('can:admin')->group(function () {
        // 勤怠一覧画面
        Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'admin_attendance_list'])->name('admin.attendance.list');
        // 修正申請の承認
        Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminAttendanceController::class, 'approve_correction_request'])->name('admin.stamp.approve');
    });
    
    // スタッフ専用
    Route::middleware('can:staff')->group(function () {
        // 勤怠関連のルート
        Route::get('/attendance', [AttendanceController::class, 'attendance_top'])->name('attendance.top');
        // 出勤・退勤のルート（登録処理）
        Route::post('/attendance/start', [AttendanceController::class, 'start_attendance'])->name('start.attendance');
        Route::post('/attendance/end', [AttendanceController::class, 'end_attendance'])->name('end.attendance');
        // 勤怠一覧のルート
        Route::get('/attendance/list', [AttendanceController::class, 'attendance_list'])->name('attendance.list');

        // 勤怠詳細のルート（更新処理）
        Route::patch('/attendance/detail/{id}', [AttendanceController::class, 'attendance_detail_update'])->name('attendance.update');

        // 休憩開始・終了のルート
        Route::post('/rest/start', [RestController::class, 'start_rest'])->name('start.rest');
        Route::post('/rest/end', [RestController::class, 'end_rest'])->name('end.rest');
    });
});
