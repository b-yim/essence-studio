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
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('pending_payment')->index();
            $table->string('payment_provider')->nullable();
            $table->string('payment_reference')->nullable()->unique();
            $table->string('currency', 3)->default('USD');
            $table->unsignedInteger('total_cents');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('phone');
            $table->text('shipping_address');
            $table->text('notes')->nullable();
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
