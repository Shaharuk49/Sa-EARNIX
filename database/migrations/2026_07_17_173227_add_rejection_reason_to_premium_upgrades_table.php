<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('premium_upgrades', function (Blueprint $table) {
            if (!Schema::hasColumn('premium_upgrades', 'rejection_reason')) {
                $table->string('rejection_reason')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('premium_upgrades', function (Blueprint $table) {
            if (Schema::hasColumn('premium_upgrades', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};