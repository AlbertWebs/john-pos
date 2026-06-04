<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->foreignId('supply_id')->nullable()->after('category_id')->constrained('supplies')->nullOnDelete();
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('supply_id')->nullable()->after('user_id')->constrained('supplies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supply_id');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supply_id');
        });
    }
};
