<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeComponent extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'name', 'weight'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class, 'component_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'component_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'component_id');
    }
}
