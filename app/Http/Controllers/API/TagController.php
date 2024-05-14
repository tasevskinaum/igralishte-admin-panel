<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Tag $tag)
    {
        $products = Product::whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })->get();

        $brands = Brand::whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })->get();

        return response()->json([
            'products' => ProductResource::collection($products),
            'brands' => BrandResource::collection($brands)
        ]);
    }
}
