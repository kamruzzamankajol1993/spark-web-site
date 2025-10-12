<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['category_id']);
            // Then, drop the column
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            // Add it back if we need to roll back
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};