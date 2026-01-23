<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rest extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_id',
        'rest_start_time',
        'rest_end_time',
    ];
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}