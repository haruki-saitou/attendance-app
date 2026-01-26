<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function attendance_top()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Unauthorized action.');
        }

        $nowJst = now('Asia/Tokyo');

        $attendance = Attendance::where('user_id', $user->id)
            ->where('year', $nowJst->year)
            ->where('month', $nowJst->month)
            ->where('date', $nowJst->day)
            ->first();

        return view('staff.attendance', compact('user', 'attendance'));
    }

    public function start_attendance(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Unauthorized action.');
        }

        $nowJst = now('Asia/Tokyo');

        $exists = Attendance::where('user_id', $user->id)
            ->where('year', $nowJst->year)
            ->where('month', $nowJst->month)
            ->where('date', $nowJst->day)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', '既に出勤しています。');
        }

        Attendance::create([
            'user_id' => $user->id,
            'year' => $nowJst->year,
            'month' => $nowJst->month,
            'date' => $nowJst->day,
            'day' => $nowJst->isoFormat('dddd'),
            'status' => '出勤中',
            'check_in_time' => $nowJst->toTimeString(),
        ]);

        return redirect()->route('attendance.top');
    }
    public function end_attendance(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Unauthorized action.');
        }

        $nowJst = now('Asia/Tokyo');
        // 1. 今日の出勤データを取得（日本時間の「今」の年月日で探します）
        $attendance = Attendance::where('user_id', $user->id)
            ->where('year', $nowJst->year)
            ->where('month', $nowJst->month)
            ->where('date', $nowJst->day)
            ->with('rests')
            ->first();

        if ($attendance) {
            // 2. 休憩戻の押し忘れを救済（休憩を終わらせずに退勤した人を助けます）
            $unfinishedRest = $attendance->rests()->whereNull('rest_end_time')->first();
            if ($unfinishedRest) {
                $restStart = Carbon::parse($unfinishedRest->rest_start_time);
                // 休憩開始から8時間以上経っていたら、休憩終了時間を休憩開始時間＋1時間に設定
                if ($nowJst->diffInHours($restStart) >= 8) {
                    $unfinishedRest->rest_end_time = $restStart->copy()->addHour()->toTimeString();
                } else {
                    // それ以外は現在時刻を休憩終了時間に設定
                    $unfinishedRest->rest_end_time = $nowJst->toTimeString();
                }
                $unfinishedRest->save();
                $attendance->load('rests'); // 最新の休憩時間を読み込み直します
            }

            // 3. 全ての休憩時間を合計（秒）
            $totalRestSeconds = (int)$attendance->rests()
            ->whereNotNull('rest_end_time')
            ->sum(DB::raw('TIME_TO_SEC(TIMEDIFF(rest_end_time, rest_start_time))'));
            // 4. 会社にいた時間の合計（秒）を計算
            $checkInTime = Carbon::today('Asia/Tokyo')->setTimeFromTimeString($attendance->check_in_time);
            // 出勤から退勤まで何秒か？（trueで必ずプラスの数字にする）
            $totalStaySeconds = $checkInTime->diffInSeconds($nowJst, true);
            // 5. 実際のお仕事時間（秒） = 会社にいた時間 - 休憩時間
            $actualWorkSeconds = max(0, $totalStaySeconds - $totalRestSeconds);
            // 6. 全てを「分」に直して保存
            $attendance->update([
                'work_duration' => (int)round($actualWorkSeconds / 60),
                'rest_duration' => (int)round($totalRestSeconds / 60),
                'check_out_time' => $nowJst->toTimeString(),
                'status' => '退勤済'
            ]);
        }else {
            return redirect()->back()->with('error', '出勤していません。');
        }

        return redirect()->route('attendance.top');
    }
}
