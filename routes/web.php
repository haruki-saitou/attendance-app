<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'attendance_top'])->name('attendance.top');
    Route::post('/attendance/start', [AttendanceController::class, 'start_attendance'])->name('start.attendance');
    Route::post('/attendance/end', [AttendanceController::class, 'end_attendance'])->name('end.attendance');
    Route::post('/rest/start', [RestController::class, 'start_rest'])->name('start.rest');
    Route::post('/rest/end', [RestController::class, 'end_rest'])->name('end.rest');
});