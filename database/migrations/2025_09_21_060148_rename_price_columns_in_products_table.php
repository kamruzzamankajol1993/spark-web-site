<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('price', 'buying_price');
            $table->renameColumn('sale_price', 'selling_price');
        });
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('buying_price', 'price');
            $table->renameColumn('selling_price', 'sale_price');
        });
    }
};