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
        'login_attempts',
        'locked_until',
        'last_login_attempt',
    ];

    protected $hidden = [
        'pin',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'pin' => 'hashed',
            'locked_until' => 'datetime',
            'last_login_attempt' => 'datetime',
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

    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function incrementLoginAttempts()
    {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;
        $this->last_login_attempt = now();
        
        // Lock account after 3 failed attempts for 30 minutes
        if ($this->login_attempts >= 3) {
            $this->locked_until = now()->addMinutes(30);
        }
        
        $this->save();
    }

    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    public function getRemainingLockTime()
    {
        if (!$this->isLocked()) {
            return null;
        }
        
        return $this->locked_until->diffForHumans();
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
