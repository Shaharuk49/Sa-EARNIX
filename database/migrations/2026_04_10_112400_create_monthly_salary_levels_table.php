<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_salary_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level_number')->unique();
            $table->string('title');
            $table->decimal('salary_amount', 12, 2)->default(0);
            $table->boolean('is_active_by_admin')->default(false);
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_levels');
    }
};