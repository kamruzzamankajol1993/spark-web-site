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
        Schema::table('extra_pages', function (Blueprint $table) {
            $table->text('warranty_policy')->nullable()->after('return_policy');
            $table->text('payment_term')->nullable()->after('warranty_policy');
            $table->text('delivery_policy')->nullable()->after('payment_term');
            $table->text('refund_policy')->nullable()->after('delivery_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extra_pages', function (Blueprint $table) {
            $table->dropColumn(['warranty_policy', 'payment_term', 'delivery_policy', 'refund_policy']);
        });
    }
};