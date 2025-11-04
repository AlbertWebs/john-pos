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
            // Create new table with updated constraint
            DB::statement('
                CREATE TABLE sales_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    invoice_number VARCHAR(255) NOT NULL UNIQUE,
                    customer_id INTEGER,
                    user_id INTEGER NOT NULL,
                    date DATE NOT NULL,
                    subtotal DECIMAL(10,2) NOT NULL,
                    tax DECIMAL(10,2) DEFAULT 0,
                    discount DECIMAL(10,2) DEFAULT 0,
                    total_amount DECIMAL(10,2) NOT NULL,
                    payment_status VARCHAR(255) NOT NULL DEFAULT \'pending\' CHECK(payment_status IN (\'pending\', \'paid\', \'partial\', \'completed\')),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');
            
            // Copy data from old table
            DB::statement('
                INSERT INTO sales_new 
                SELECT * FROM sales
            ');
            
            // Drop old table
            Schema::dropIfExists('sales');
            
            // Rename new table
            DB::statement('ALTER TABLE sales_new RENAME TO sales');
            
            // Recreate indexes
            DB::statement('CREATE INDEX sales_invoice_number_index ON sales(invoice_number)');
            DB::statement('CREATE INDEX sales_date_index ON sales(date)');
        } else {
            // For other databases, modify the column directly
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('payment_status', ['pending', 'paid', 'partial', 'completed'])
                    ->default('pending')
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
            DB::statement('
                CREATE TABLE sales_old (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    invoice_number VARCHAR(255) NOT NULL UNIQUE,
                    customer_id INTEGER,
                    user_id INTEGER NOT NULL,
                    date DATE NOT NULL,
                    subtotal DECIMAL(10,2) NOT NULL,
                    tax DECIMAL(10,2) DEFAULT 0,
                    discount DECIMAL(10,2) DEFAULT 0,
                    total_amount DECIMAL(10,2) NOT NULL,
                    payment_status VARCHAR(255) NOT NULL DEFAULT \'pending\' CHECK(payment_status IN (\'pending\', \'paid\', \'partial\')),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');
            
            DB::statement('
                INSERT INTO sales_old 
                SELECT * FROM sales WHERE payment_status != \'completed\'
            ');
            
            Schema::dropIfExists('sales');
            DB::statement('ALTER TABLE sales_old RENAME TO sales');
            
            DB::statement('CREATE INDEX sales_invoice_number_index ON sales(invoice_number)');
            DB::statement('CREATE INDEX sales_date_index ON sales(date)');
        } else {
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('payment_status', ['pending', 'paid', 'partial'])
                    ->default('pending')
                    ->change();
            });
        }
    }
};
