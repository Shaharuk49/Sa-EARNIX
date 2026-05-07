<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_bonus_section_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bonus_section_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_unlocked')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'bonus_section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bonus_section_progress');
    }
};