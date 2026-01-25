<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'date',
        'day',
        'status',
        'check_in_time',
        'check_out_time',
        'work_duration',
        'rest_duration',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function rests()
    {
        return $this->hasMany(Rest::class);
    }

    public function getIsRestingAttribute()
    {
        return $this->rests()->whereNull('rest_end_time')->exists();
    }
}