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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'username')) {
            // Drop existing unique index if it exists
            if (DB::getDriverName() === 'mysql') {
                try {
                    DB::statement('ALTER TABLE `users` DROP INDEX `users_username_unique`');
                } catch (\Exception $e) {
                    // Index might not exist or have a different name
                    try {
                        DB::statement('ALTER TABLE `users` DROP INDEX `username`');
                    } catch (\Exception $e2) {
                        // Try to get the actual index name from information_schema
                        try {
                            $indexes = DB::select("SHOW INDEX FROM `users` WHERE Column_name = 'username'");
                            foreach ($indexes as $index) {
                                try {
                                    DB::statement("ALTER TABLE `users` DROP INDEX `{$index->Key_name}`");
                                } catch (\Exception $e3) {
                                    // Ignore if can't drop
                                }
                            }
                        } catch (\Exception $e4) {
                            // Ignore if can't query indexes
                        }
                    }
                }
            } elseif (DB::getDriverName() === 'sqlite') {
                // SQLite handles index dropping differently - we'll recreate the table if needed
                // For now, just proceed with column modification
                // SQLite doesn't support MODIFY COLUMN directly, so we'll skip this for SQLite
                // The original migration should have created it correctly
                return;
            }

            // Modify the column to have a length of 191 using raw SQL
            // This works for MySQL without requiring doctrine/dbal
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE `users` MODIFY `username` VARCHAR(191) NOT NULL');
            } elseif (DB::getDriverName() === 'sqlite') {
                // SQLite doesn't support MODIFY COLUMN, skip this migration for SQLite
                // The original migration should have created it correctly with the fix we made
                return;
            } else {
                // For other databases, use Schema
                Schema::table('users', function (Blueprint $table) {
                    $table->string('username', 191)->change();
                });
            }

            // Recreate the unique index (only for MySQL)
            if (DB::getDriverName() === 'mysql') {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('username', 'users_username_unique');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'username')) {
            if (DB::getDriverName() === 'mysql') {
                // Drop the unique index
                try {
                    DB::statement('ALTER TABLE `users` DROP INDEX `users_username_unique`');
                } catch (\Exception $e) {
                    // Index might not exist
                }

                // Change back to default length (255) using raw SQL
                DB::statement('ALTER TABLE `users` MODIFY `username` VARCHAR(255) NOT NULL');

                // Recreate unique index (may fail on MySQL with utf8mb4)
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('username');
                });
            } elseif (DB::getDriverName() === 'sqlite') {
                // SQLite doesn't support MODIFY COLUMN, skip rollback
                return;
            } else {
                // For other databases, use Schema
                try {
                    DB::statement('ALTER TABLE `users` DROP INDEX `users_username_unique`');
                } catch (\Exception $e) {
                    // Index might not exist
                }
                Schema::table('users', function (Blueprint $table) {
                    $table->string('username')->change();
                });
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('username');
                });
            }
        }
    }
};
