<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function attendance_top()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Unauthorized action.');
        }

        return view('attendance', compact('user'));
    }
}