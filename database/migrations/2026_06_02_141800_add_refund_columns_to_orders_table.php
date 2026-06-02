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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'refund_reason')) {
                $table->text('refund_reason')->nullable()->after('cancel_reason');
            }
            if (!Schema::hasColumn('orders', 'refund_evidence')) {
                $table->string('refund_evidence')->nullable()->after('refund_reason');
            }
            if (!Schema::hasColumn('orders', 'refund_feedback')) {
                $table->text('refund_feedback')->nullable()->after('refund_evidence');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'refund_reason')) {
                $table->dropColumn('refund_reason');
            }
            if (Schema::hasColumn('orders', 'refund_evidence')) {
                $table->dropColumn('refund_evidence');
            }
            if (Schema::hasColumn('orders', 'refund_feedback')) {
                $table->dropColumn('refund_feedback');
            }
        });
    }
};
