<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_make_id',
        'model_name',
        'year_start',
        'year_end',
    ];

    protected function casts(): array
    {
        return [
            'year_start' => 'integer',
            'year_end' => 'integer',
        ];
    }

    // Relationships
    public function vehicleMake()
    {
        return $this->belongsTo(VehicleMake::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}
