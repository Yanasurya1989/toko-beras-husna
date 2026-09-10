<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {

            $table->id();

            $table->string('invoice')->unique();

            $table->date('tanggal');

            $table->string('nama_pembeli');

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->integer('qty');

            $table->bigInteger('harga');

            $table->bigInteger('subtotal');

            $table->enum('payment_type', ['cash', 'credit']);

            $table->enum('status', ['paid', 'unpaid']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
