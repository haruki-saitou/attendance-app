<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;


Route::middleware(['auth','verified'])->group(function () {
    // 勤怠関連のルート
    Route::get('/attendance', [AttendanceController::class, 'attendance_top'])->name('attendance.top');
    // 出勤・退勤のルート
    Route::post('/attendance/start', [AttendanceController::class, 'start_attendance'])->name('start.attendance');
    Route::post('/attendance/end', [AttendanceController::class, 'end_attendance'])->name('end.attendance');
    // 勤怠一覧のルート
    Route::get('/attendance/list', [AttendanceController::class, 'attendance_history'])->name('attendance.list');
    // 休憩開始・終了のルート
    Route::post('/rest/start', [RestController::class, 'start_rest'])->name('start.rest');
    Route::post('/rest/end', [RestController::class, 'end_rest'])->name('end.rest');
});
