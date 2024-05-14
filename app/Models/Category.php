<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name'
    ];

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeWithActiveBrands($query)
    {
        return $query->with(['brands' => function ($query) {
            $query->whereHas('status', function ($query) {
                $query->where('name', 'Активен');
            });
        }]);
    }
}
