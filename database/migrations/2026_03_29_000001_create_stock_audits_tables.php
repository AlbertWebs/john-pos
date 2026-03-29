<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_audits', function (Blueprint $table) {
            $table->id();
            $table->date('period_from');
            $table->date('period_to');
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('stock_audit_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_audit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained('inventory')->cascadeOnDelete();
            $table->unsignedInteger('physical_stock')->nullable();
            $table->timestamps();

            $table->unique(['stock_audit_id', 'part_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_audit_lines');
        Schema::dropIfExists('stock_audits');
    }
};
