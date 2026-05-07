<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_leader_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leader_badge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'leader_badge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_leader_badges');
    }
};