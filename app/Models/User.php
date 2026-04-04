<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo_path',
        'bio',
        'nis',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'religion',
        'phone',
        'father_name',
        'mother_name',
        'parent_address',
        'father_job',
        'mother_job',
        'exp',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class, 'student_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceStudent::class, 'student_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'student_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function addExp(int $points)
    {
        $this->exp += $points;
        $newLevel = floor($this->exp / 150) + 1;
        
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
        }
        $this->save();
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
