<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'pin',
        'role',
        'status',
    ];

    protected $hidden = [
        'pin',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'pin' => 'hashed',
        ];
    }

    // Note: We'll hash the PIN manually when creating/updating users
    // Using casts instead for automatic hashing

    public function verifyPin($pin)
    {
        return Hash::check($pin, $this->pin);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class, 'changed_by');
    }
}
