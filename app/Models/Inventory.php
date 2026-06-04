<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'image',
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

    // Many-to-many relationship for multiple vehicle models
    public function vehicleModels()
    {
        return $this->belongsToMany(VehicleModel::class, 'inventory_vehicle_model')
            ->withTimestamps();
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

    /**
     * Product types exempt from barcode printing (product name only, whole-word match).
     */
    public static function noPrintBarcodeTerms(): array
    {
        return [
            'rivet',
            'washer',
            'tie wrap',
            'metric bolt',
            'bolt',
            'nut',
            'return spring',
            'horse clip',
            'wire clip',
        ];
    }

    /**
     * Exclude items from barcode printing when product name contains an exempt term as a whole word (category not checked).
     */
    public function scopeExcludeNoPrintBarcodeCategories($query)
    {
        $terms = self::noPrintBarcodeTerms();
        $table = $this->getTable();

        foreach ($terms as $term) {
            $query->whereRaw('LOWER(' . $table . '.name) != ?', [$term])
                ->whereRaw('LOWER(' . $table . '.name) != ?', [$term . 's'])
                ->whereRaw('LOWER(' . $table . '.name) NOT LIKE ?', ['% ' . $term . ' %'])
                ->whereRaw('LOWER(' . $table . '.name) NOT LIKE ?', [$term . ' %'])
                ->whereRaw('LOWER(' . $table . '.name) NOT LIKE ?', ['% ' . $term])
                ->whereRaw('LOWER(' . $table . '.name) NOT LIKE ?', ['% ' . $term . 's %'])
                ->whereRaw('LOWER(' . $table . '.name) NOT LIKE ?', [$term . 's %'])
                ->whereRaw('LOWER(' . $table . '.name) NOT LIKE ?', ['% ' . $term . 's']);
        }

        return $query;
    }

    /**
     * Whether this item should be excluded from barcode generation/printing (product name only, whole-word match).
     */
    public function shouldExcludeFromBarcodePrint(): bool
    {
        $name = strtolower((string) $this->name);

        foreach (self::noPrintBarcodeTerms() as $term) {
            $pattern = '/\b' . preg_quote($term, '/') . 's?\b/';
            if (preg_match($pattern, $name)) {
                return true;
            }
        }

        return false;
    }

    // Helper methods
    public function isLowStock()
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    /**
     * Add stock and record a purchase movement for audit/reporting.
     */
    public function addStock(int $quantity, ?Carbon $receivedAt, ?string $notes, int $userId): InventoryMovement
    {
        return DB::transaction(function () use ($quantity, $receivedAt, $notes, $userId) {
            $this->increment('stock_quantity', $quantity);

            return InventoryMovement::create([
                'part_id' => $this->id,
                'change_quantity' => $quantity,
                'movement_type' => 'purchase',
                'user_id' => $userId,
                'notes' => $notes,
                'timestamp' => $receivedAt ?? now(),
            ]);
        });
    }

    public static function generateSku(array $data): string
    {
        $categoryPrefix = '';
        if (!empty($data['category_id'])) {
            $category = Category::find($data['category_id']);
            if ($category) {
                $cleanedName = preg_replace('/[^A-Za-z0-9]/', '', $category->name);
                $categoryPrefix = strtoupper(substr($cleanedName, 0, 3));
            }
        }

        $partNumberBase = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $data['part_number'] ?? ''));
        $timestamp = now()->format('Ymd');

        $sku = ($categoryPrefix ? $categoryPrefix . '-' : '') . ($partNumberBase ?: 'ITEM') . '-' . $timestamp;

        $counter = 1;
        $originalSku = $sku;
        while (self::where('sku', $sku)->exists()) {
            $sku = $originalSku . '-' . $counter;
            $counter++;
        }

        return $sku;
    }
}
