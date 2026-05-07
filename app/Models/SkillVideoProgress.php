<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillVideoProgress extends Model
{
    protected $fillable = ['user_id', 'skill_course_video_id'];
}
