<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->ensureSuppliesReferencable();
        $this->ensureTableInnoDb('inventory');
        $this->ensureTableInnoDb('inventory_movements');

        $this->addSupplyIdColumn('inventory', 'category_id', 'inventory_supply_id_foreign');
        $this->addSupplyIdColumn('inventory_movements', 'user_id', 'inventory_movements_supply_id_foreign');
    }

    /**
     * Foreign keys require InnoDB parent with BIGINT UNSIGNED primary key.
     */
    protected function ensureSuppliesReferencable(): void
    {
        if (! Schema::hasTable('supplies')) {
            return;
        }

        $this->ensureTableInnoDb('supplies');

        $idColumn = DB::selectOne("SHOW COLUMNS FROM `supplies` WHERE Field = 'id'");

        if ($idColumn && ! str_contains(strtolower($idColumn->Type ?? ''), 'unsigned')) {
            DB::statement('ALTER TABLE `supplies` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        $hasPrimary = collect(DB::select('SHOW INDEX FROM `supplies` WHERE Key_name = ?', ['PRIMARY']))->isNotEmpty();

        if (! $hasPrimary) {
            DB::statement('ALTER TABLE `supplies` ADD PRIMARY KEY (`id`)');
        }
    }

    protected function ensureTableInnoDb(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $status = DB::selectOne(
            'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );

        if ($status && strtoupper((string) $status->ENGINE) !== 'INNODB') {
            DB::statement("ALTER TABLE `{$table}` ENGINE=InnoDB");
        }
    }

    protected function addSupplyIdColumn(string $table, string $afterColumn, string $foreignName): void
    {
        if (! Schema::hasColumn($table, 'supply_id')) {
            Schema::table($table, function (Blueprint $blueprint) use ($afterColumn) {
                $blueprint->unsignedBigInteger('supply_id')->nullable()->after($afterColumn);
            });
        } else {
            DB::statement("ALTER TABLE `{$table}` MODIFY `supply_id` BIGINT UNSIGNED NULL");
        }

        DB::statement(
            "UPDATE `{$table}` SET `supply_id` = NULL
             WHERE `supply_id` IS NOT NULL
               AND `supply_id` NOT IN (SELECT `id` FROM `supplies`)"
        );

        if ($this->foreignKeyExists($table, $foreignName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($foreignName) {
            $blueprint->foreign('supply_id', $foreignName)
                ->references('id')
                ->on('supplies')
                ->nullOnDelete();
        });
    }

    protected function foreignKeyExists(string $table, string $constraintName): bool
    {
        $row = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND CONSTRAINT_NAME = ?
               AND CONSTRAINT_TYPE = ?',
            [$table, $constraintName, 'FOREIGN KEY']
        );

        return $row !== null;
    }

    public function down(): void
    {
        if ($this->foreignKeyExists('inventory_movements', 'inventory_movements_supply_id_foreign')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropForeign('inventory_movements_supply_id_foreign');
            });
        }

        if (Schema::hasColumn('inventory_movements', 'supply_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropColumn('supply_id');
            });
        }

        if ($this->foreignKeyExists('inventory', 'inventory_supply_id_foreign')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropForeign('inventory_supply_id_foreign');
            });
        }

        if (Schema::hasColumn('inventory', 'supply_id')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropColumn('supply_id');
            });
        }
    }
};
