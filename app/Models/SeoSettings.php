<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'structured_data',
        'custom_meta_tags',
    ];

    protected $casts = [
        'structured_data' => 'array',
    ];
}
