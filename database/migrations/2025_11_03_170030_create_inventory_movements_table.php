<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('inventory')->onDelete('cascade');
            $table->integer('change_quantity'); // Positive for additions, negative for sales
            $table->enum('movement_type', ['sale', 'purchase', 'return', 'adjust', 'damage'])->default('sale');
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related sale, purchase, return, etc.
            $table->string('reference_type')->nullable(); // Model type: Sale, Purchase, etc.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('timestamp');
            $table->timestamps();
            
            $table->index(['part_id', 'movement_type']);
            $table->index('timestamp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
