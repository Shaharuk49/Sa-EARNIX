<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('affiliate_id')->nullable()->unique()->after('id');
            $table->foreignId('upline_user_id')->nullable()->after('affiliate_id')->constrained('users')->nullOnDelete();
            $table->string('profile_photo')->nullable()->after('password');
            $table->boolean('is_active')->default(false)->after('profile_photo');
            $table->boolean('is_premium')->default(false)->after('is_active');
            $table->string('transaction_password')->nullable()->after('is_premium');
            $table->enum('preferred_language', ['en', 'bn'])->default('en')->after('transaction_password');
            $table->timestamp('joined_at')->nullable()->after('preferred_language');
            $table->timestamp('last_login_at')->nullable()->after('joined_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('upline_user_id');
            $table->dropColumn([
                'affiliate_id',
                'profile_photo',
                'is_active',
                'is_premium',
                'transaction_password',
                'preferred_language',
                'joined_at',
                'last_login_at',
            ]);
        });
    }
};