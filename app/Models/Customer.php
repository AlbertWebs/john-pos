<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'loyalty_points',
    ];

    protected function casts(): array
    {
        return [
            'loyalty_points' => 'integer',
        ];
    }

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Helper methods
    public function getTotalPurchases()
    {
        return $this->sales()->sum('total_amount');
    }

    public function getTotalTransactions()
    {
        return $this->sales()->count();
    }

    public function outstandingBalance(): float
    {
        return round(
            $this->sales()
                ->with('payments')
                ->outstanding()
                ->get()
                ->sum(fn (Sale $sale) => $sale->balanceDue()),
            2
        );
    }

    public function openCreditSales()
    {
        return $this->sales()
            ->with(['payments', 'saleItems.part', 'user'])
            ->outstanding()
            ->orderByDesc('date');
    }
}

