<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NextOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'part_number',
        'requested_quantity',
        'customer_name',
        'customer_contact',
        'notes',
        'status',
        'requested_by',
        'ordered_at',
        'fulfilled_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ORDERED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}

