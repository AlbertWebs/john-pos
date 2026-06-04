<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sales', 'is_credit')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->boolean('is_credit')->default(false)->after('payment_status');
            });
        }

        if (! Schema::hasColumn('sales', 'due_date')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->date('due_date')->nullable()->after('is_credit');
            });
        }

        if (! Schema::hasColumn('sales', 'credit_notes')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->text('credit_notes')->nullable()->after('due_date');
            });
        }
    }

    public function down(): void
    {
        $columns = array_filter([
            Schema::hasColumn('sales', 'credit_notes') ? 'credit_notes' : null,
            Schema::hasColumn('sales', 'due_date') ? 'due_date' : null,
            Schema::hasColumn('sales', 'is_credit') ? 'is_credit' : null,
        ]);

        if ($columns !== []) {
            Schema::table('sales', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
