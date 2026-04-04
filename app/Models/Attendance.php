<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'date', 'start_time', 'end_time', 'password'];

    protected $casts = [
        'date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attendanceStudents()
    {
        return $this->hasMany(AttendanceStudent::class);
    }

    public function isActive(): bool
    {
        $now = now();
        $start = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time);
        $end = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time);
        return $now->between($start, $end);
    }
}
