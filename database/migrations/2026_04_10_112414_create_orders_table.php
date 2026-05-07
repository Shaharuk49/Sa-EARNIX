<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('base_price', 12, 2);
            $table->decimal('vat_percent', 5, 2)->default(20.00);
            $table->decimal('final_admin_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->decimal('profit_amount', 12, 2)->default(0);
            $table->decimal('delivery_charge', 12, 2)->default(120);
            $table->string('shop_name')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('district');
            $table->string('upazila');
            $table->text('delivery_address');
            $table->text('additional_instruction')->nullable();
            $table->enum('status', ['pending', 'processing', 'delivered', 'cancelled'])->default('pending');
            $table->enum('profit_status', ['hold', 'released', 'cancelled'])->default('hold');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};