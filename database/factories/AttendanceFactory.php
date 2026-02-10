<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => '勤務外',
            'check_in_at' => now()->setTime(9, 0, 0),
            'check_out_at' => now()->setTime(18, 0, 0),
            'comment' => '通常勤務',
        ];
    }
}
