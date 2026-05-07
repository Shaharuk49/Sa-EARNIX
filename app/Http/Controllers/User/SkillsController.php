<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SkillCourse;
use App\Models\SkillCourseVideo;
use App\Models\SkillVideoProgress;
use Illuminate\Support\Facades\Auth;

class SkillsController extends Controller
{
    public function index()
    {
        $courses = SkillCourse::where('is_active', true)
            ->withCount(['videos' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('user.skills.index', compact('courses'));
    }

    public function show(SkillCourse $course)
    {
        abort_if(!$course->is_active, 404);

        $videos = $course->videos()->where('is_active', true)->orderBy('sort_order')->get();

        $userId = Auth::id();

        $watchedIds = SkillVideoProgress::where('user_id', $userId)
            ->whereIn('skill_course_video_id', $videos->pluck('id'))
            ->pluck('skill_course_video_id')
            ->toArray();

        return view('user.skills.show', compact('course', 'videos', 'watchedIds'));
    }

    public function markWatched(SkillCourseVideo $video)
    {
        SkillVideoProgress::firstOrCreate([
            'user_id'               => Auth::id(),
            'skill_course_video_id' => $video->id,
        ]);

        return response()->json(['success' => true]);
    }
}
