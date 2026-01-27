<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 勤怠画面表示
    public function attendance_top()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in_at', today())->first();

        return view('staff.attendance', compact('user', 'attendance'));
    }

    // 出勤処理
    public function start_attendance(Request $request)
    {
        $now = now();
        $user = Auth::user();

        $exists = Attendance::where('user_id', $user->id)
            ->whereDate('check_in_at', $now->today())->exists();

        if ($exists) {
            return redirect()->back()->with('error', '既に出勤しています。');
        }

        Attendance::create([
            'user_id' => $user->id,
            'status' => '出勤中',
            'check_in_at' => $now,
        ]);

        return redirect()->route('attendance.top');
    }
    // 退勤処理
    public function end_attendance(Request $request)
    {
        $now = now();
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in_at', $now->today())
            ->whereNull('check_out_at')
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', '出勤していません。');
        }

        if ($attendance->is_resting) {
            $attendance->rests()->whereNull('end_at')->update([
                'end_at' => $now,
            ]);
        }

        $attendance->update([
            'check_out_at' => $now,
            'status' => '退勤済'
        ]);

        return redirect()->route('attendance.top');
    }

    public function attendance_list(Request $request)
    {
        $user = Auth::user();

        $month = $request->query('month', today()->format('Y-m'));
        $date = Carbon::parse($month);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('check_in_at', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()])
            ->with('rests')
            ->orderBy('check_in_at', 'asc')
            ->get();

        $prev_month = $date->copy()->subMonth()->format('Y-m');
        $next_month = $date->copy()->addMonth()->format('Y-m');
        return view('staff.attendance_list', compact('attendances', 'date', 'prev_month', 'next_month'));
    }
}
