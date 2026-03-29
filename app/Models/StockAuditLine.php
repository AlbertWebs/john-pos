<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAuditLine extends Model
{
    protected $fillable = [
        'stock_audit_id',
        'part_id',
        'physical_stock',
    ];

    protected function casts(): array
    {
        return [
            'physical_stock' => 'integer',
        ];
    }

    public function stockAudit(): BelongsTo
    {
        return $this->belongsTo(StockAudit::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'part_id');
    }
}
