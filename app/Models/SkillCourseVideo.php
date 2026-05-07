<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillCourseVideo extends Model
{
    protected $fillable = ['skill_course_id', 'title', 'video_url', 'sort_order', 'is_active'];

    public function course()
    {
        return $this->belongsTo(SkillCourse::class, 'skill_course_id');
    }

    public function progress()
    {
        return $this->hasMany(SkillVideoProgress::class);
    }
}
