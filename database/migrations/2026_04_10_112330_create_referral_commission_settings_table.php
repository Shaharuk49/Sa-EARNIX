<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_commission_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('generation_number')->unique();
            $table->decimal('amount', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commission_settings');
    }
};