<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('video_url');
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_course_videos');
    }
};