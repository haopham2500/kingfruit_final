<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->decimal('discount_value', 20, 2);
            $table->date('expiry_date')->nullable();
            $table->enum('type', ['fixed', 'percent'])->default('fixed')->nullable();
            $table->decimal('min_order_value', 20, 2)->default(0.00)->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
