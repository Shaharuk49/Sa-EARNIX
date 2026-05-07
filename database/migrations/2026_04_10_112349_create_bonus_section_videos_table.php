<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonus_section_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('video_url');
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_section_videos');
    }
};