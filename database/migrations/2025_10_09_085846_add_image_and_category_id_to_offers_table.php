<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
            $table->foreignId('category_id')->nullable()->after('status')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['image', 'category_id']);
        });
    }
};