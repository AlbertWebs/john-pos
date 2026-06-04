<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_credit')->default(false)->after('payment_status');
            $table->date('due_date')->nullable()->after('is_credit');
            $table->text('credit_notes')->nullable()->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['is_credit', 'due_date', 'credit_notes']);
        });
    }
};
