<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_item_id')->nullable()->constrained('sale_items')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('inventory')->onDelete('cascade');
            $table->integer('quantity_returned');
            $table->decimal('refund_amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'credited'])->default('completed');
            $table->text('reason')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
