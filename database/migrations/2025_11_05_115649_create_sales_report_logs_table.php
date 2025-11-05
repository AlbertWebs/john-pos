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
        Schema::create('sales_report_logs', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('report_type')->default('daily'); // daily, hourly, alert
            $table->text('summary')->nullable();
            $table->string('file_path')->nullable(); // Path to PDF/Excel file if generated
            $table->string('recipient_email');
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index('report_date');
            $table->index('report_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_report_logs');
    }
};
