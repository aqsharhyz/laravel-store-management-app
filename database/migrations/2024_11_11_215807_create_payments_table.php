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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained(table: 'orders', column: 'id')->onDelete('cascade')->unique();
            $table->foreignId('user_id')->constrained(table: 'users', column: 'id')->onDelete('cascade');
            $table->enum('payment_method', ['Credit Card', 'PayPal', 'Bank Transfer', 'Cash']);
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['Paid', 'Pending', 'Failed', 'Refunded']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
