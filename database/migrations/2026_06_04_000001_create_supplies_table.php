<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('supplies')) {
            Schema::create('supplies', function (Blueprint $table) {
                $table->id();
                // 191 chars max for utf8mb4 unique index (MySQL 1000-byte key limit)
                $table->string('name', 191);
                $table->string('contact_person')->nullable();
                $table->string('phone', 50)->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->text('notes')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                $table->unique('name');
                $table->index('status');
            });

            return;
        }

        $this->ensureSuppliesTableReady();
    }

    /**
     * Table may exist from a failed first run (name as varchar(255), no indexes).
     */
    protected function ensureSuppliesTableReady(): void
    {
        $this->shrinkNameColumnForIndex();

        $indexes = collect(DB::select('SHOW INDEX FROM supplies'))->pluck('Key_name')->unique();

        if (! $indexes->contains('supplies_name_unique')) {
            Schema::table('supplies', function (Blueprint $table) {
                $table->unique('name');
            });
        }

        if (! $indexes->contains('supplies_status_index')) {
            Schema::table('supplies', function (Blueprint $table) {
                $table->index('status');
            });
        }
    }

    protected function shrinkNameColumnForIndex(): void
    {
        $column = DB::selectOne("SHOW COLUMNS FROM `supplies` WHERE Field = 'name'");

        if (! $column || ! preg_match('/varchar\((\d+)\)/i', $column->Type ?? '', $matches)) {
            return;
        }

        if ((int) $matches[1] > 191) {
            DB::statement('ALTER TABLE `supplies` MODIFY `name` VARCHAR(191) NOT NULL');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
