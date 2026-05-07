<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillCourse extends Model
{
    protected $fillable = ['title', 'description', 'thumbnail', 'is_active', 'sort_order'];

    public function videos()
    {
        return $this->hasMany(SkillCourseVideo::class);
    }
}
