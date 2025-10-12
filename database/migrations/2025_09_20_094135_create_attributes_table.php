<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
              $table->id();
    $table->unsignedBigInteger('category_id');
    $table->string('name');
    $table->enum('input_type', ['text', 'number', 'select', 'checkbox', 'radio']);
    $table->timestamps();

    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

    // Prevent duplicate attribute names inside same category
   
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
