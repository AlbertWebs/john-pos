<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;

    protected $fillable = [
        'make_name',
    ];

    // Relationships
    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}
