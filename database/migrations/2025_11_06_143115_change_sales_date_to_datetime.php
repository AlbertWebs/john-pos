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
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('
                CREATE TABLE sales_temp (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    invoice_number VARCHAR(255) NOT NULL UNIQUE,
                    customer_id INTEGER,
                    user_id INTEGER NOT NULL,
                    date DATETIME NOT NULL,
                    subtotal DECIMAL(10,2) NOT NULL,
                    tax DECIMAL(10,2) DEFAULT 0,
                    discount DECIMAL(10,2) DEFAULT 0,
                    total_amount DECIMAL(10,2) NOT NULL,
                    payment_status VARCHAR(255) NOT NULL DEFAULT "pending" CHECK(payment_status IN ("pending", "paid", "partial", "completed")),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');

            DB::statement('
                INSERT INTO sales_temp (
                    id, invoice_number, customer_id, user_id, date, subtotal, tax, discount, total_amount, payment_status, created_at, updated_at
                )
                SELECT id, invoice_number, customer_id, user_id, datetime(date), subtotal, tax, discount, total_amount, payment_status, created_at, updated_at
                FROM sales
            ');

            Schema::dropIfExists('sales');

            DB::statement('ALTER TABLE sales_temp RENAME TO sales');
            DB::statement('CREATE INDEX sales_invoice_number_index ON sales(invoice_number)');
            DB::statement('CREATE INDEX sales_date_index ON sales(date)');
        } else {
            Schema::table('sales', function (Blueprint $table) {
                $table->dateTime('date')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('
                CREATE TABLE sales_temp (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    invoice_number VARCHAR(255) NOT NULL UNIQUE,
                    customer_id INTEGER,
                    user_id INTEGER NOT NULL,
                    date DATE NOT NULL,
                    subtotal DECIMAL(10,2) NOT NULL,
                    tax DECIMAL(10,2) DEFAULT 0,
                    discount DECIMAL(10,2) DEFAULT 0,
                    total_amount DECIMAL(10,2) NOT NULL,
                    payment_status VARCHAR(255) NOT NULL DEFAULT "pending" CHECK(payment_status IN ("pending", "paid", "partial", "completed")),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');

            DB::statement('
                INSERT INTO sales_temp (
                    id, invoice_number, customer_id, user_id, date, subtotal, tax, discount, total_amount, payment_status, created_at, updated_at
                )
                SELECT id, invoice_number, customer_id, user_id, date(date), subtotal, tax, discount, total_amount, payment_status, created_at, updated_at
                FROM sales
            ');

            Schema::dropIfExists('sales');

            DB::statement('ALTER TABLE sales_temp RENAME TO sales');
            DB::statement('CREATE INDEX sales_invoice_number_index ON sales(invoice_number)');
            DB::statement('CREATE INDEX sales_date_index ON sales(date)');
        } else {
            Schema::table('sales', function (Blueprint $table) {
                $table->date('date')->change();
            });
        }
    }
};
