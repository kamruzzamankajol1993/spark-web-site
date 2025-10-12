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
        Schema::table('attribute_category', function (Blueprint $table) {
            // Add the new column, defaulting to false (not required)
            $table->boolean('is_required')->default(false)->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_category', function (Blueprint $table) {
            $table->dropColumn('is_required');
        });
    }
};