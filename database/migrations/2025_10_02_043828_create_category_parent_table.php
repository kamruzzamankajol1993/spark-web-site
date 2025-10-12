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
        Schema::create('category_parent', function (Blueprint $table) {
             $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('parent_id');

            // Define foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            // Define a composite primary key
            $table->primary(['category_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_parent');
    }
};
