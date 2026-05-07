<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelancing_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('group_link');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancing_categories');
    }
};