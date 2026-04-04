<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'comment',
        'parent_id',
        'is_pinned',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function replies()
    {
        return $this->hasMany(MaterialComment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(MaterialComment::class, 'parent_id');
    }
}
