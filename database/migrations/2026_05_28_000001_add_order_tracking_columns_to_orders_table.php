<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('receiver_name')->nullable();
                $table->string('phone_number')->nullable();
                $table->text('address')->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->string('status')->default('pending');
                $table->text('note')->nullable();
                $table->string('payment_method')->nullable();
                $table->text('cancel_reason')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orders', 'receiver_name')) {
                $table->string('receiver_name')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('receiver_name');
            }

            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable()->after('address');
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('note');
            }

            if (!Schema::hasColumn('orders', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('orders', 'receiver_name')) {
                $table->dropColumn('receiver_name');
            }

            if (Schema::hasColumn('orders', 'phone_number')) {
                $table->dropColumn('phone_number');
            }

            if (Schema::hasColumn('orders', 'note')) {
                $table->dropColumn('note');
            }

            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            if (Schema::hasColumn('orders', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
        });
    }
};
