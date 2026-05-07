<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_salary_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('monthly_salary_level_id')->constrained()->cascadeOnDelete();
            $table->string('claim_month', 7);
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['claimed', 'blocked'])->default('claimed');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'monthly_salary_level_id', 'claim_month'], 'unique_monthly_salary_claim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_claims');
    }
};