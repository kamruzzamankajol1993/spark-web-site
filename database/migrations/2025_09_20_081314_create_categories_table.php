<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // Category name
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent category
            $table->string('slug')->unique();             // For SEO-friendly URL
            $table->text('description')->nullable();      // Optional
            $table->string('image')->nullable();  
            $table->string('status',11)->nullable();         // Optional (icon or image)
            $table->timestamps();

            // Foreign key for parent category
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
