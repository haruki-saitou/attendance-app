{{-- 1. レイアウト（外枠）を使う --}}
@extends('layouts.app')

@section('content')
    <div class="status-area">
        <p>{{ $user->name }}お疲れ様です！</p> {{-- Figmaの仕様に基づき --}}
    </div>

    <div class="clock-area text-center my-10">
        <div id="real-time-date" class="text-2xl font-bold"></div>
        <div id="real-time-clock" class="text-6xl font-bold text-center mt-2"></div>
    </div>

    <div class="button-area flex gap-4 max-w-4xl mx-auto mt-10">
        {{-- ★部品（コンポーネント）を呼び出す★ --}}
        <x-attendance-button text="勤務開始" action="/attendance/start" color="blue" />
        <x-attendance-button text="勤務終了" action="/attendance/end" color="blue" :disabled="true" />
    </div>
    <div class="button-area flex gap-4 max-w-4xl mx-auto mt-4">
        <x-attendance-button text="休憩開始" action="/rest/start" color="blue" />
        <x-attendance-button text="休憩終了" action="/rest/end" color="blue" />
    </div>
@endsection
@section('scripts')
    <script>
        // リアルタイム時計のスクリプト
        function updateClock() {
            const now = new Date();
            const year = now.getFullYear();
            const month = now.getMonth() + 1; // 月は0から始まるため+1
            const date = now.getDate();

            const dayName = ['日', '月', '火', '水', '木', '金', '土'];
            const dayOfWeek = dayName[now.getDay()];

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            const dateElement = document.getElementById('real-time-date');
            const clockElement = document.getElementById('real-time-clock');

            if (dateElement) {
                dateElement.textContent = `${year}年${month}月${date}日(${dayOfWeek})`;
            }
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}`;
            }
        }

        setInterval(updateClock, 1000);
        updateClock(); // 初回呼び出し
    </script>
@endsection
