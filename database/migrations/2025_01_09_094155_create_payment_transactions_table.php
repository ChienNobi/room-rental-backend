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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('type')->comment('deposit, charge, refund');
            $table->double('amount');
            $table->string('description')->nullable();
            $table->string('status')->comment('pending, success, failed');
            $table->string('gateway')->comment('system, vn-pay, momo, zalo-pay, banking');
            $table->string('reference_id')->nullable()->comment('reference_id from gateway, null when system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
