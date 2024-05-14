<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
