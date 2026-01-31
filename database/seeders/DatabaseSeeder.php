<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 管理者を作る（ログイン用）
        User::create([
            'name' => '管理者太郎',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 1,
            'email_verified_at' => now(),
        ]);

        $staffCount = 10;
        for ($s = 1; $s <= $staffCount; $s++) {
            $user = User::create([
                'name' => "スタッフ{$s}",
                'email' => "staff{$s}@example.com",
                'password' => Hash::make('password'),
                'role' => 0,
                'email_verified_at' => now(),
            ]);
        }
        // 2. 1月の1ヶ月分のデータを作る（プロの怠惰：ループで自動作成）
        $date = Carbon::create(2026, 1, 1);
        for ($i = 0; $i < $date->daysInMonth; $i++) {
            $currentDate = $date->copy()->addDays($i);

            // 土日はスキップ（weekendを自動判別）
            if ($currentDate->isWeekend()) {
                continue;
            }

            // 出勤データ
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'status' => '退勤済',
                'check_in_at' => $currentDate->copy()->setTime(9, 0, 0),
                'check_out_at' => $currentDate->copy()->setTime(18, 0, 0),
            ]);

            // 休憩データ（1日2回分）
            Rest::create([
                'attendance_id' => $attendance->id,
                'start_at' => $currentDate->copy()->setTime(12, 0, 0),
                'end_at' => $currentDate->copy()->setTime(13, 0, 0),
            ]);

            Rest::create([
                'attendance_id' => $attendance->id,
                'start_at' => $currentDate->copy()->setTime(16, 0, 0),
                'end_at' => $currentDate->copy()->setTime(16, 15, 0),
            ]);
        }
    }
}
