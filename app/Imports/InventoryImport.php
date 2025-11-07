<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventoryImport implements ToCollection, WithHeadingRow
{
    public array $summary = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => [],
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowData = $row instanceof Collection ? $row->toArray() : (array) $row;
            $rowNumber = $index + 2; // Account for heading row

            if ($this->rowIsEmpty($rowData)) {
                $this->summary['skipped']++;
                continue;
            }

            $partNumber = trim((string) ($rowData['part_number'] ?? ''));

            if ($partNumber === '') {
                $this->addError($rowNumber, 'Missing part_number.');
                continue;
            }

            try {
                DB::transaction(function () use ($rowData, $partNumber) {
                    $inventory = Inventory::where('part_number', $partNumber)->first();
                    $isNew = !$inventory;

                    if ($isNew) {
                        $inventory = new Inventory();
                        $inventory->part_number = $partNumber;
                    }

                    $attributes = $this->buildAttributes($rowData, $inventory, $isNew);

                    $inventory->fill($attributes);

                    if ($isNew && empty($inventory->sku)) {
                        $inventory->sku = Inventory::generateSku(array_merge($attributes, [
                            'part_number' => $inventory->part_number,
                            'category_id' => $attributes['category_id'] ?? $inventory->category_id,
                        ]));
                    }

                    $inventory->save();

                    $this->summary[$isNew ? 'created' : 'updated']++;
                });
            } catch (\Throwable $e) {
                $this->addError($rowNumber, $e->getMessage());
            }
        }
    }

    private function buildAttributes(array $row, Inventory $inventory, bool $isNew): array
    {
        $attributes = [];

        if (($sku = $this->valueOrNull($row['sku'] ?? null)) !== null) {
            $attributes['sku'] = $sku;
        }

        if (($barcode = $this->valueOrNull($row['barcode'] ?? null)) !== null) {
            $attributes['barcode'] = $barcode;
        }

        $name = $this->valueOrNull($row['name'] ?? null);
        if ($isNew && $name === null) {
            throw new \RuntimeException('Name is required for new inventory items.');
        }
        if ($name !== null) {
            $attributes['name'] = $name;
        }

        if (($description = $this->valueOrNull($row['description'] ?? null)) !== null) {
            $attributes['description'] = $description;
        }

        $inventory->loadMissing('vehicleMake');

        $make = null;
        if (($makeName = $this->valueOrNull($row['vehicle_make'] ?? null)) !== null) {
            $make = $this->resolveVehicleMake($makeName);
            $attributes['vehicle_make_id'] = $make->id;
        } elseif ($inventory->vehicle_make_id) {
            $make = $inventory->vehicleMake;
        }

        $vehicleMakeIdForModel = $attributes['vehicle_make_id'] ?? $inventory->vehicle_make_id;

        if (($modelName = $this->valueOrNull($row['vehicle_model'] ?? null)) !== null) {
            $model = $this->resolveVehicleModel($modelName, $vehicleMakeIdForModel);
            $attributes['vehicle_model_id'] = $model->id;
        }

        if (($yearRange = $this->valueOrNull($row['year_range'] ?? null)) !== null) {
            $attributes['year_range'] = $yearRange;
        }

        if (($location = $this->valueOrNull($row['location'] ?? null)) !== null) {
            $attributes['location'] = $location;
        }

        if (($brandName = $this->valueOrNull($row['brand'] ?? null)) !== null) {
            $brand = $this->resolveBrand($brandName);
            $attributes['brand_id'] = $brand->id;
        }

        if (($categoryName = $this->valueOrNull($row['category'] ?? null)) !== null) {
            $category = $this->resolveCategory($categoryName);
            $attributes['category_id'] = $category->id;
        }

        $costPrice = $this->parseDecimal($row['cost_price'] ?? null, 'cost_price');
        if ($costPrice !== null) {
            $attributes['cost_price'] = $costPrice;
        } elseif ($isNew) {
            throw new \RuntimeException('cost_price is required for new inventory items.');
        }

        $sellingPrice = $this->parseDecimal($row['selling_price'] ?? null, 'selling_price');
        if ($sellingPrice !== null) {
            $attributes['selling_price'] = $sellingPrice;
        } elseif ($isNew) {
            throw new \RuntimeException('selling_price is required for new inventory items.');
        }

        $minPrice = $this->parseDecimal($row['min_price'] ?? null, 'min_price');
        $targetSelling = $sellingPrice ?? $inventory->selling_price;

        if ($minPrice !== null) {
            if ($targetSelling !== null && $minPrice > $targetSelling) {
                throw new \RuntimeException('min_price cannot be greater than selling_price.');
            }
            $attributes['min_price'] = $minPrice;
        } elseif ($isNew) {
            $attributes['min_price'] = $sellingPrice ?? $costPrice ?? 0;
        }

        $stockQuantity = $this->parseInteger($row['stock_quantity'] ?? null, 'stock_quantity');
        if ($stockQuantity !== null) {
            $attributes['stock_quantity'] = $stockQuantity;
        } elseif ($isNew) {
            throw new \RuntimeException('stock_quantity is required for new inventory items.');
        }

        $reorderLevel = $this->parseInteger($row['reorder_level'] ?? null, 'reorder_level');
        if ($reorderLevel !== null) {
            $attributes['reorder_level'] = $reorderLevel;
        } elseif ($isNew) {
            $attributes['reorder_level'] = 0;
        }

        $status = $this->valueOrNull($row['status'] ?? null);
        if ($status !== null) {
            $normalizedStatus = Str::lower($status);
            if (!in_array($normalizedStatus, ['active', 'inactive'], true)) {
                throw new \RuntimeException('status must be either active or inactive.');
            }
            $attributes['status'] = $normalizedStatus;
        } elseif ($isNew) {
            $attributes['status'] = 'active';
        }

        return $attributes;
    }

    private function valueOrNull($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            return $trimmed === '' ? null : $trimmed;
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        return null;
    }

    private function parseDecimal($value, string $field): ?float
    {
        $stringValue = $this->valueOrNull($value);

        if ($stringValue === null) {
            return null;
        }

        $normalized = str_replace([',', ' '], ['', ''], $stringValue);

        if (!is_numeric($normalized)) {
            throw new \RuntimeException("{$field} must be a numeric value.");
        }

        return round((float) $normalized, 2);
    }

    private function parseInteger($value, string $field): ?int
    {
        $stringValue = $this->valueOrNull($value);

        if ($stringValue === null) {
            return null;
        }

        if (!is_numeric($stringValue)) {
            throw new \RuntimeException("{$field} must be an integer value.");
        }

        return (int) round((float) $stringValue);
    }

    private function resolveBrand(string $name): Brand
    {
        $existing = Brand::whereRaw('LOWER(brand_name) = ?', [Str::lower($name)])->first();

        return $existing ?: Brand::create(['brand_name' => $name]);
    }

    private function resolveCategory(string $name): Category
    {
        $existing = Category::whereRaw('LOWER(name) = ?', [Str::lower($name)])->first();

        return $existing ?: Category::create(['name' => $name]);
    }

    private function resolveVehicleMake(string $name): VehicleMake
    {
        $existing = VehicleMake::whereRaw('LOWER(make_name) = ?', [Str::lower($name)])->first();

        return $existing ?: VehicleMake::create(['make_name' => $name]);
    }

    private function resolveVehicleModel(string $modelName, ?int $vehicleMakeId): VehicleModel
    {
        $query = VehicleModel::whereRaw('LOWER(model_name) = ?', [Str::lower($modelName)]);

        if ($vehicleMakeId) {
            $query->where('vehicle_make_id', $vehicleMakeId);
        }

        $model = $query->first();

        if (!$model && $vehicleMakeId) {
            $model = VehicleModel::create([
                'model_name' => $modelName,
                'vehicle_make_id' => $vehicleMakeId,
            ]);
        }

        if (!$model) {
            throw new \RuntimeException('Vehicle model "' . $modelName . '" could not be resolved. Provide the vehicle make to create it, or ensure the model already exists.');
        }

        return $model;
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (!is_null($value) && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function addError(int $rowNumber, string $message): void
    {
        $this->summary['errors'][] = "Row {$rowNumber}: {$message}";
    }
}


