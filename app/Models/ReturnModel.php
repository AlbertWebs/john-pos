<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'part_id',
        'quantity_returned',
        'refund_amount',
        'status',
        'reason',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'refund_amount' => 'decimal:2',
        ];
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function part()
    {
        return $this->belongsTo(Inventory::class, 'part_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

