<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // Specify table name (singular)
    protected $table = 'inventory';

    protected $fillable = [
        'part_number',
        'sku',
        'barcode',
        'name',
        'description',
        'brand_id',
        'category_id',
        'vehicle_make_id',
        'vehicle_model_id',
        'year_range',
        'cost_price',
        'min_price',
        'selling_price',
        'stock_quantity',
        'reorder_level',
        'location',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'min_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'reorder_level' => 'integer',
        ];
    }

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vehicleMake()
    {
        return $this->belongsTo(VehicleMake::class);
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'part_id');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'part_id');
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class, 'part_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'reorder_level');
    }

    // Helper methods
    public function isLowStock()
    {
        return $this->stock_quantity <= $this->reorder_level;
    }
}
