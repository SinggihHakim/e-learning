<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'file_path', 'video_link', 'type'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($material) {
            if ($material->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($material->file_path);
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function comments()
    {
        return $this->hasMany(MaterialComment::class);
    }
}
