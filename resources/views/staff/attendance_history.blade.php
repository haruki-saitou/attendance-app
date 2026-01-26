@extends('layouts.app')

@section('content')
    <div
        class="container mx-w-[1400px] mx-auto px-8 py-4 flex flex-col items-center justify-center min-h-[calc(100vh-80px)]">
        <h1 class="border-l-4 border-black pl-4 text-3xl font-bold mb-6">勤務一覧</h1>
        <div class="w-full max-w-[900px] mt-4">
            {{ $pagination_links }}
        </div>
        <table class="w-full max-w-[900px] bg-white rounded-lg overflow-hidden mt-6 text-center text-[#737373] font-bold">
            <tr class="flex flex-between text-lg border-b-3 border-[#E1E1E1]">
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach ($attendances as $attendance)
                <tr class="text-center border-b-2 border-[#E1E1E1]">
                    <td class="py-2">{{ $attendance->date->format('Y/m/d (D)') }}</td>
                    <td class="py-2">{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '' }}
                    </td>
                    <td class="py-2">{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '' }}
                    </td>
                    <td class="py-2">{{ gmdate('H:i', $attendance->rest_duration) ? gmdate('H:i', $attendance->rest_duration) : '' }}</td>
                    <td class="py-2">{{ gmdate('H:i', $attendance->work_duration) ? gmdate('H:i', $attendance->work_duration) : '' }}</td>
                    <td class="py-2" for="attendance_show">
                        <a href="{{ route('staff.attendance_history', ['attendance' => $attendance->id]) }}"
                            id="attendance_show" class="text-black cursor-pointer hover:text-gray-700">詳細</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
