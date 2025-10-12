<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('title');
             $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('status')->default(true)->comment('1=Active, 0=Inactive');
            $table->timestamps();
        });

        // This is the pivot table for linking products
        Schema::create('flash_sale_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('flash_price', 10, 2);
            $table->integer('quantity');
            $table->integer('sold')->default(0); // To track how many have been sold in the sale
            $table->timestamps();

            $table->unique(['flash_sale_id', 'product_id']); // A product can only be in a sale once
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_product');
        Schema::dropIfExists('flash_sales');
    }
};
