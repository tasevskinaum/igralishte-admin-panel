<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'percent',
        'discount_category_id',
        'status_id'
    ];

    public function discount_category()
    {
        return $this->belongsTo(DiscountCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function scopeByStatus($query, $statusName)
    {
        return $query->whereHas('status', function ($query) use ($statusName) {
            $query->where('name', $statusName);
        });
    }
}
