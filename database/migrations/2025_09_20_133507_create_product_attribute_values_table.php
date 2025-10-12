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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
              $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
    $table->string('value')->nullable();
    

    // Prevent same attribute being stored multiple times for same product
    $table->unique(['product_id', 'attribute_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
