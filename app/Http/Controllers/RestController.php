<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class RestController extends Controller
{
    public function start_rest(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Unauthorized action.');
        }

        $nowJst = now('Asia/Tokyo');



        // 1. 今日の出勤データを取得
        $attendance = Attendance::where('user_id', $user->id)
            ->where('year', $nowJst->year)
            ->where('month', $nowJst->month)
            ->where('date', $nowJst->day)
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', '出勤していません。');
        }

        // 2. モデルのアクセサ ($attendance->is_resting) を使って判定
        if (!$attendance->is_resting) {
            $attendance->rests()->create([
                'rest_start_time' => $nowJst->toTimeString(),
            ]);
            // 整合性のため、勤怠本体のステータスも「休憩中」に変えておきます
            $attendance->update(['status' => '休憩中']);
        }

        return redirect()->route('attendance.top');
    }

    public function end_rest(Request $request)
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

        if (!$attendance) {
            return redirect()->back()->with('error', '出勤していません。');
        }

        // 3. 休憩中であれば、終わっていない休憩記録を閉じる
        if ($attendance->is_resting) {
            $rest = $attendance->rests()->whereNull('rest_end_time')->first();
            if ($rest) {
                $rest->update([
                    'rest_end_time' => $nowJst->toTimeString(),
                ]);
                // ステータスを「出勤中」に戻します
                $attendance->update(['status' => '出勤中']);
            }
        }

        return redirect()->route('attendance.top');
    }
}
