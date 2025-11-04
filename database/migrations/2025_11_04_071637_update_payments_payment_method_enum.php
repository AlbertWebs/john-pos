<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER TABLE for ENUM constraints
        // We need to recreate the table with the updated constraint
        if (DB::getDriverName() === 'sqlite') {
            // First, update existing data to match new enum values
            DB::statement("UPDATE payments SET payment_method = 'Cash' WHERE payment_method = 'cash'");
            DB::statement("UPDATE payments SET payment_method = 'M-Pesa' WHERE payment_method = 'mpesa'");
            
            // Create new table with updated constraint
            DB::statement('
                CREATE TABLE payments_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    sale_id INTEGER NOT NULL,
                    payment_method VARCHAR(255) NOT NULL DEFAULT \'Cash\' CHECK(payment_method IN (\'Cash\', \'M-Pesa\')),
                    amount DECIMAL(10,2) NOT NULL,
                    transaction_reference VARCHAR(255),
                    payment_date DATETIME NOT NULL,
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
                )
            ');
            
            // Copy data from old table
            DB::statement('
                INSERT INTO payments_new 
                SELECT * FROM payments
            ');
            
            // Drop old table
            Schema::dropIfExists('payments');
            
            // Rename new table
            DB::statement('ALTER TABLE payments_new RENAME TO payments');
            
            // Recreate index
            DB::statement('CREATE INDEX payments_transaction_reference_index ON payments(transaction_reference)');
        } else {
            // For other databases, update existing data first
            DB::statement("UPDATE payments SET payment_method = 'Cash' WHERE payment_method = 'cash'");
            DB::statement("UPDATE payments SET payment_method = 'M-Pesa' WHERE payment_method = 'mpesa'");
            
            // Then modify the column
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('payment_method', ['Cash', 'M-Pesa'])
                    ->default('Cash')
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        if (DB::getDriverName() === 'sqlite') {
            // Update data back to lowercase
            DB::statement("UPDATE payments SET payment_method = 'cash' WHERE payment_method = 'Cash'");
            DB::statement("UPDATE payments SET payment_method = 'mpesa' WHERE payment_method = 'M-Pesa'");
            
            DB::statement('
                CREATE TABLE payments_old (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    sale_id INTEGER NOT NULL,
                    payment_method VARCHAR(255) NOT NULL DEFAULT \'cash\' CHECK(payment_method IN (\'cash\', \'mpesa\')),
                    amount DECIMAL(10,2) NOT NULL,
                    transaction_reference VARCHAR(255),
                    payment_date DATETIME NOT NULL,
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
                )
            ');
            
            DB::statement('
                INSERT INTO payments_old 
                SELECT * FROM payments
            ');
            
            Schema::dropIfExists('payments');
            DB::statement('ALTER TABLE payments_old RENAME TO payments');
            
            DB::statement('CREATE INDEX payments_transaction_reference_index ON payments(transaction_reference)');
        } else {
            // Update data back
            DB::statement("UPDATE payments SET payment_method = 'cash' WHERE payment_method = 'Cash'");
            DB::statement("UPDATE payments SET payment_method = 'mpesa' WHERE payment_method = 'M-Pesa'");
            
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('payment_method', ['cash', 'mpesa'])
                    ->default('cash')
                    ->change();
            });
        }
    }
};
