<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('generate_etims_receipt')->default(false)->after('payment_status');
            $table->string('etims_invoice_number')->nullable()->after('generate_etims_receipt');
            $table->string('etims_uuid')->nullable()->after('etims_invoice_number');
            $table->datetime('etims_approval_date')->nullable()->after('etims_uuid');
            $table->boolean('etims_verified')->default(false)->after('etims_approval_date');
            
            $table->index('etims_invoice_number');
            $table->index('etims_verified');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['etims_invoice_number']);
            $table->dropIndex(['etims_verified']);
            $table->dropColumn([
                'generate_etims_receipt',
                'etims_invoice_number',
                'etims_uuid',
                'etims_approval_date',
                'etims_verified',
            ]);
        });
    }
};

