<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropColumn([
                'product_name',
                'unit',
                'price',
                'quantity',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->string('product_name')->nullable();

            $table->string('unit')->nullable();

            $table->decimal('price', 15, 2)->nullable();

            $table->decimal('quantity', 10, 2)->nullable();
        });
    }
};
