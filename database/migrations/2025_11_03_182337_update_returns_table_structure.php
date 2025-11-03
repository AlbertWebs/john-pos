<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            // Add sale_item_id if it doesn't exist
            if (!Schema::hasColumn('returns', 'sale_item_id')) {
                $table->foreignId('sale_item_id')->nullable()->after('sale_id')->constrained('sale_items')->onDelete('cascade');
            }
            
            // Rename quantity to quantity_returned if needed
            if (Schema::hasColumn('returns', 'quantity') && !Schema::hasColumn('returns', 'quantity_returned')) {
                $table->renameColumn('quantity', 'quantity_returned');
            } elseif (!Schema::hasColumn('returns', 'quantity_returned')) {
                $table->integer('quantity_returned')->after('part_id');
            }
            
            // Add status if it doesn't exist
            if (!Schema::hasColumn('returns', 'status')) {
                $table->enum('status', ['pending', 'completed', 'credited'])->default('completed')->after('refund_amount');
            }
            
            // Remove refund_type if it exists (we'll use status instead)
            if (Schema::hasColumn('returns', 'refund_type')) {
                $table->dropColumn('refund_type');
            }
            
            // Remove return_date if it exists (using timestamps instead)
            if (Schema::hasColumn('returns', 'return_date')) {
                $table->dropColumn('return_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            if (Schema::hasColumn('returns', 'sale_item_id')) {
                $table->dropForeign(['sale_item_id']);
                $table->dropColumn('sale_item_id');
            }
            
            if (Schema::hasColumn('returns', 'status')) {
                $table->dropColumn('status');
            }
            
            if (!Schema::hasColumn('returns', 'quantity')) {
                $table->integer('quantity');
            }
            
            if (!Schema::hasColumn('returns', 'refund_type')) {
                $table->enum('refund_type', ['refund', 'credit'])->default('refund');
            }
            
            if (!Schema::hasColumn('returns', 'return_date')) {
                $table->dateTime('return_date');
            }
        });
    }
};
