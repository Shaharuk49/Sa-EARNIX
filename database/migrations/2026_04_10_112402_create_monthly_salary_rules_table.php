<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_salary_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_salary_level_id')->constrained()->cascadeOnDelete();
            $table->text('rule_text');
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_rules');
    }
};