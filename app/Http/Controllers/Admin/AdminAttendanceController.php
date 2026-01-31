<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminAttendanceController extends Controller
{
/**
* 全スタッフの修正申請を一覧で出す（管理者用）
*/

    public function admin_attendance_list(Request $request)
    {
        $date = Carbon::parse($request->query('date', today()->format('Y-m-d')));
        $staffIds = User::where('role', '0')->pluck('id');
        $attendances = Attendance::whereIn('user_id', $staffIds)
            ->whereDate('check_in_at', $date)
            ->with('user', 'rests')
            ->orderBy('check_in_at', 'asc')
            ->get();
        $prev_date = $date->copy()->subDay()->format('Y-m-d');
        $next_date = $date->copy()->addDay()->format('Y-m-d');
        return view('admin.attendance_list', compact('attendances', 'date', 'prev_date', 'next_date'));
    }
    public function approve_correction_request(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        // スタッフ全員の「承認待ち」または「承認済み」を取得
        $query = Attendance::with(['user', 'attendanceCorrect']);

        if ($tab === 'approved') {
        $query->where('status', '承認済');
        } else {
        $query->where('status', '承認待ち');
        }

        $correct_requests = $query->orderBy('updated_at', 'asc')->get();

        // 管理者専用の blade を表示
        return view('admin.stamp_list', compact('correct_requests', 'tab'));
    }
}
