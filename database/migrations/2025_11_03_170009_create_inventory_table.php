<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('vehicle_make_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('vehicle_model_id')->nullable()->constrained()->onDelete('set null');
            $table->string('year_range')->nullable(); // e.g. "2015–2021"
            $table->decimal('cost_price', 10, 2);
            $table->decimal('min_price', 10, 2); // Minimum selling price
            $table->decimal('selling_price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->string('location')->nullable(); // shelf/bin
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index(['category_id', 'vehicle_make_id', 'vehicle_model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
