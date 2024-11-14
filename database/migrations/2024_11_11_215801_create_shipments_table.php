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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('shipping_address');
            $table->string('tracking_number')->nullable();
            $table->dateTime('estimated_delivery_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
            // $table->enum('status', ['pending', 'shipped', 'delivered', 'returned'])->default('pending');
            // $table->enum('shipping_method', ['standard', 'express', 'overnight'])->default('standard');
            // $table->foreignId('carrier_id')->constrained('carriers')->onDelete('cascade');
            // $table->foreignId('shipper_id')->constrained('shippers')->onDelete('cascade');
            // $table->text('notes')->nullable();
            // penyerahan barang ke ekspedisi
            // $table->dateTime('shipped_at')->nullable();
            // $table->softDeletes()
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
