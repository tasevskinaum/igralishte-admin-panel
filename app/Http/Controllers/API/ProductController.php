<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Brand\ProductResource as BrandProductResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['category', 'color', 'size', 'minPrice', 'maxPrice', 'brand']);

        if (empty($filters)) {
            return ProductResource::collection(Product::byStatus('Активен')->get());
        }

        $query = $this->applyFilters(Product::query(), $filters);

        $products = $query->get();

        return ProductResource::collection($products);
    }

    public function getByBrand(Brand $brand)
    {
        if (!($brand->status->name === 'Активен'))
            return response()->json(['error' => 'Брендот не постои!'], 404);

        $activeProducts = $brand->products->filter(function ($product) {
            return $product->status->name === 'Активен';
        });

        return BrandProductResource::collection($activeProducts);
    }

    public function getByDiscount(Discount $discount)
    {
        if (!($discount->status->name === 'Активен'))
            return response()->json(['error' => 'Попустот не постои!'], 404);

        $activeProducts = $discount->products->filter(function ($product) {
            return $product->status->name === 'Активен';
        });

        return ProductResource::collection($activeProducts);
    }

    public function getByCategory(Category $category)
    {
        if (!($category))
            return response()->json(['error' => 'Категоријата не постои!'], 404);

        $activeProducts = $category->products->filter(function ($product) {
            return $product->status->name === 'Активен';
        });

        return ProductResource::collection($activeProducts);
    }

    private function applyFilters($query, $filters)
    {
        if (isset($filters['category'])) {
            $categories = is_array($filters['category']) ? $filters['category'] : [$filters['category']];
            $query->whereIn('category_id', $categories);
        }

        if (isset($filters['color'])) {
            $query->whereHas('colors', function ($query) use ($filters) {
                $query->whereIn('colors.id', $filters['color']);
            });
        }

        if (isset($filters['size'])) {
            $query->whereHas('sizes', function ($query) use ($filters) {
                $query->whereIn('size_id', $filters['size'])
                    ->where('product_size.quantity', '>', 0);
            });
        }

        if (isset($filters['minPrice'])) {
            $query->where('price', '>=', $filters['minPrice']);
        }

        if (isset($filters['maxPrice'])) {
            $query->where('price', '<=', $filters['maxPrice']);
        }

        if (isset($filters['brand'])) {
            $query->whereIn('brand_id', $filters['brand']);
        }

        if (isset($filters['has_discount'])) {
            $hasDiscount = filter_var($filters['has_discount'], FILTER_VALIDATE_BOOLEAN);
            if ($hasDiscount) {
                $query->whereHas('discount');
            } else {
                $query->whereDoesntHave('discount');
            }
        }

        $query->whereHas('status', function ($query) {
            $query->where('name', 'Активен');
        });

        return $query;
    }
}
