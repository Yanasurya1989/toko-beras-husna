<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            $table->dateTime('transaction_date');

            $table->string('customer_name');

            $table->string('product_name');

            $table->string('unit');

            $table->decimal('price', 15, 2);

            $table->decimal('quantity', 10, 2);

            $table->decimal('total_price', 15, 2);

            $table->decimal('amount_paid', 15, 2)->default(0);

            $table->decimal('remaining_debt', 15, 2)->default(0);

            $table->enum('payment_status', [
                'paid',
                'unpaid'
            ])->default('paid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
