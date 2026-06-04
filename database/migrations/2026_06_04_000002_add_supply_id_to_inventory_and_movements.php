<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('inventory', 'supply_id')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->foreignId('supply_id')->nullable()->after('category_id')->constrained('supplies')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('inventory_movements', 'supply_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->foreignId('supply_id')->nullable()->after('user_id')->constrained('supplies')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('inventory_movements', 'supply_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropConstrainedForeignId('supply_id');
            });
        }

        if (Schema::hasColumn('inventory', 'supply_id')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropConstrainedForeignId('supply_id');
            });
        }
    }
};
