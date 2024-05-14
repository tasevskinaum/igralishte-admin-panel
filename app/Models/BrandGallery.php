<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'image_url'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
