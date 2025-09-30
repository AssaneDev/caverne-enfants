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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('billing_name')->after('payment_status')->nullable();
            $table->string('billing_email')->after('billing_name')->nullable();
            $table->text('billing_address')->after('billing_email')->nullable();
            $table->string('billing_city')->after('billing_address')->nullable();
            $table->string('billing_postal_code')->after('billing_city')->nullable();
            $table->string('billing_country', 2)->after('billing_postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
