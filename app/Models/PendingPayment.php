<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'transaction_reference',
        'phone_number',
        'amount',
        'account_reference',
        'first_name',
        'middle_name',
        'last_name',
        'transaction_type',
        'status',
        'transaction_date',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'datetime',
            'raw_data' => 'array',
        ];
    }

    protected $appends = ['full_name'];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAllocated($query)
    {
        return $query->where('status', 'allocated');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAllocated(): bool
    {
        return $this->status === 'allocated';
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts) ?: 'Unknown';
    }
}
