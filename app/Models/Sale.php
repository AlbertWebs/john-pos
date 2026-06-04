<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'date',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'payment_status',
        'is_credit',
        'due_date',
        'credit_notes',
        'generate_etims_receipt',
        'etims_invoice_number',
        'etims_uuid',
        'etims_approval_date',
        'etims_verified',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'is_credit' => 'boolean',
            'due_date' => 'date',
            'generate_etims_receipt' => 'boolean',
            'etims_verified' => 'boolean',
            'etims_approval_date' => 'datetime',
        ];
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function returns()
    {
        return $this->hasMany(\App\Models\ReturnModel::class);
    }

    public function amountPaid(): float
    {
        if ($this->relationLoaded('payments')) {
            return round((float) $this->payments->sum('amount'), 2);
        }

        return round((float) $this->payments()->sum('amount'), 2);
    }

    public function balanceDue(): float
    {
        return max(0, round((float) $this->total_amount - $this->amountPaid(), 2));
    }

    public function isSettled(): bool
    {
        return $this->balanceDue() < 0.01;
    }

    public function syncPaymentStatus(): void
    {
        $paid = $this->amountPaid();
        $total = (float) $this->total_amount;

        if ($paid < 0.01) {
            $this->payment_status = $this->is_credit ? 'pending' : 'pending';
        } elseif ($paid >= $total - 0.01) {
            $this->payment_status = $this->is_credit ? 'paid' : 'completed';
        } else {
            $this->payment_status = 'partial';
        }

        $this->save();
    }

    public function scopeOutstanding($query)
    {
        return $query->whereIn('payment_status', ['pending', 'partial']);
    }

    public function scopeCredit($query)
    {
        return $query->where('is_credit', true);
    }
}

