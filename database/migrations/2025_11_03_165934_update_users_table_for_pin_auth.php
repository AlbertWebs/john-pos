<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if users table exists with old structure
        if (Schema::hasTable('users')) {
            // Check if email column exists (old structure)
            if (Schema::hasColumn('users', 'email')) {
                // SQLite doesn't support dropping columns directly
                if (DB::getDriverName() === 'sqlite') {
                    $this->recreateUsersTableForSqlite();
                } else {
                    // For MySQL/PostgreSQL, drop normally
                    Schema::table('users', function (Blueprint $table) {
                        // Drop indexes first
                        try {
                            $table->dropUnique(['email']);
                        } catch (\Exception $e) {
                            // Index might not exist
                        }
                        
                        // Drop old columns
                        $table->dropColumn(['email', 'email_verified_at', 'password']);
                        
                        // Add new columns
                        $table->string('username', 191)->unique()->after('name');
                        $table->string('pin')->after('username');
                        $table->enum('role', ['super_admin', 'cashier'])->default('cashier')->after('pin');
                        $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
                    });
                }
            }
            // If email doesn't exist, check if we need to add the new columns
            elseif (!Schema::hasColumn('users', 'username')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('username', 191)->unique()->after('name');
                    $table->string('pin')->after('username');
                    $table->enum('role', ['super_admin', 'cashier'])->default('cashier')->after('pin');
                    $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
                });
            }
        }
    }

    /**
     * Recreate users table for SQLite (since it doesn't support drop column)
     */
    private function recreateUsersTableForSqlite(): void
    {
        // Get existing users data
        $users = DB::table('users')->get();
        
        // Create temporary table with new structure
        Schema::create('users_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 191)->unique();
            $table->string('pin');
            $table->string('role')->default('cashier'); // SQLite doesn't support enum, use string
            $table->string('status')->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        // Copy data from old table to new table
        foreach ($users as $user) {
            $username = $user->email ?? 'user_' . $user->id;
            $pin = $user->password ?? Hash::make('1234');
            
            DB::table('users_new')->insert([
                'id' => $user->id,
                'name' => $user->name ?? 'User',
                'username' => $username,
                'pin' => $pin,
                'role' => 'cashier',
                'status' => 'active',
                'remember_token' => $user->remember_token ?? null,
                'created_at' => $user->created_at ?? now(),
                'updated_at' => $user->updated_at ?? now(),
            ]);
        }

        // Drop old table
        Schema::dropIfExists('users');

        // Rename new table
        Schema::rename('users_new', 'users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'username')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn(['username', 'pin', 'role', 'status']);
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                });
            }
        }
    }
};
