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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //!
            $table->foreignId('user_id')->constrained(table: 'users', column: 'id')->cascadeOnDelete();
            $table->date('order_date');
            $table->enum('status', ['pending', 'processing', 'completed', 'declined'])->default('pending');
            $table->decimal('total_price', 10, 2);
            // $table->string('note')->nullable();
            // $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
