<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'change_quantity',
        'movement_type',
        'reference_id',
        'reference_type',
        'user_id',
        'notes',
        'timestamp',
    ];

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'change_quantity' => 'integer',
        ];
    }

    // Relationships
    public function part()
    {
        return $this->belongsTo(Inventory::class, 'part_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
