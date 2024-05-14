<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'size_advice',
        'maintenance_guidelines',
        'discount_id',
        'brand_id',
        'category_id',
        'in_stock',
        'status_id'
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withPivot('quantity');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    // use only in orders
    public function getProductPrice()
    {
        if ($this->discount) {
            $discountedPrice = $this->price - ($this->price * ($this->discount->percent / 100));
            return $discountedPrice;
        }

        return $this->price;
    }

    public function scopeByStatus($query, $statusName)
    {
        return $query->whereHas('status', function ($query) use ($statusName) {
            $query->where('name', $statusName);
        });
    }
}
