<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
            $table->string('transaction_reference')->unique(); // M-Pesa transaction code
            $table->string('phone_number')->nullable(); // Payer's phone number
            $table->decimal('amount', 10, 2);
            $table->string('account_reference')->nullable(); // Account reference from payment
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('transaction_type')->default('C2B'); // C2B or STK Push
            $table->enum('status', ['pending', 'allocated', 'cancelled'])->default('pending');
            $table->dateTime('transaction_date');
            $table->text('raw_data')->nullable(); // Store raw callback data
            $table->timestamps();
            
            $table->index('transaction_reference');
            $table->index('status');
            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_payments');
    }
};
