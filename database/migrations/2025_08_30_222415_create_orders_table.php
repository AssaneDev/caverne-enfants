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
            $table->ulid('id')->primary();
            $table->string('order_number')->unique();
            $table->ulid('user_id')->nullable();
            $table->string('status')->default('pending');
            $table->integer('subtotal_cents');
            $table->integer('shipping_cents');
            $table->integer('tax_cents')->default(0);
            $table->integer('total_cents');
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_status')->nullable();
            
            $table->string('ship_first_name')->nullable();
            $table->string('ship_last_name')->nullable();
            $table->string('ship_line1')->nullable();
            $table->string('ship_line2')->nullable();
            $table->string('ship_city')->nullable();
            $table->string('ship_state')->nullable();
            $table->string('ship_postcode')->nullable();
            $table->string('ship_country')->nullable();
            
            $table->string('tracking_carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('user_id');
            $table->index('payment_status');
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
