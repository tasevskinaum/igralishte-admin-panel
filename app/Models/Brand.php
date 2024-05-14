<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status_id'
    ];

    public function scopeByStatus($query, $statusName)
    {
        return $query->whereHas('status', function ($query) use ($statusName) {
            $query->where('name', $statusName);
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(BrandGallery::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
