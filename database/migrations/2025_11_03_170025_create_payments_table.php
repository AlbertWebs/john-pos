<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['Cash', 'M-Pesa'])->default('Cash');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_reference')->nullable(); // M-Pesa transaction code
            $table->dateTime('payment_date');
            $table->timestamps();
            
            $table->index('transaction_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
