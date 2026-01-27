@extends('layouts.app')

@section('content')
    <div
        class="container max-w-[1400px] mx-auto px-8 py-4 flex flex-col items-center justify-center min-h-[calc(100vh-80px)]">
        <h1 class="border-l-4 border-black pl-4 text-3xl font-bold mb-6">勤務一覧</h1>
        <div class="flex items-center justify-center gap-8 mb-6">
            {{-- 前月へ --}}
            <a href="{{ route('attendance.list', ['month' => $prev_month]) }}"
                class="border-2 border-black px-2 py-1 rounded hover:bg-gray-100">&lt;</a>
            {{-- 表示中の年月 --}}
            <span class="text-3xl font-bold">{{ $date->format('Y/m') }}</span>
            {{-- 翌月へ --}}
            <a href="{{ route('attendance.list', ['month' => $next_month]) }}"
                class="border-2 border-black px-2 py-1 rounded hover:bg-gray-100">&gt;</a>
        </div>
        <table class="w-full max-w-[900px] bg-white rounded-lg overflow-hidden mt-6 text-[#737373] font-bold">
            <thead>
                <tr class="text-lg border-b-3 border-[#E1E1E1]">
                    <th class="py-4 px-2">日付</th>
                    <th class="py-4 px-2">出勤</th>
                    <th class="py-4 px-2">退勤</th>
                    <th class="py-4 px-2">休憩</th>
                    <th class="py-4 px-2">合計</th>
                    <th class="py-4 px-2">詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr class="text-center border-b-2 border-[#E1E1E1] hover:bg-[#F9F9F9]">
                        {{-- 1. 日付表示：check_in_at を利用 --}}
                        <td class="py-4 px-2">{{ $attendance->check_in_at->format('Y/m/d (D)') }}</td>

                        {{-- 2. 出勤・退勤：モデルの名前 (at) と合わせる --}}
                        <td class="py-4 px-2">{{ $attendance->check_in_at->format('H:i') }}</td>
                        <td class="py-4 px-2">
                            {{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : '-' }}</td>
                        {{-- 3. 休憩・合計：モデルで作った「魔法の言葉」を呼ぶだけ！ --}}
                        <td class="py-4 px-2">{{ $attendance->formatted_total_rest_time }}</td>
                        <td class="py-4 px-2">{{ $attendance->formatted_total_worked_time }}</td>

                        <td class="py-4 px-2">
                            <a href="{{ route('staff.attendance_history', ['attendance' => $attendance->id]) }}"
                                class="text-black cursor-pointer hover:text-gray-700 underline">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
