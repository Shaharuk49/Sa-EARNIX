<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonus_section_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_section_id')->constrained()->cascadeOnDelete();
            $table->enum('rule_type', ['direct_referrals', 'total_referrals', 'premium_required']);
            $table->string('rule_value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_section_rules');
    }
};