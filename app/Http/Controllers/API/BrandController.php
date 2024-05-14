<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {

        if ($request->has('brand')) {
            $brand = Brand::where('name', $request->brand)->first();

            if ($brand && $brand->status->name === 'Активен') {
                return new BrandResource($brand);
            }

            return response()->json(['message' => 'Брендот не постои!'], 404);
        }

        return BrandResource::collection(Brand::byStatus('Активен')->get());
    }

    public function show(Brand $brand)
    {
        if (!($brand->status->name === 'Активен'))
            return response()
                ->json(['error' => 'Брендот не постои!'], 404);

        return new BrandResource($brand);
    }

    public function brandByCategory(Category $category)
    {
        return BrandResource::collection($category->brands()->byStatus('Активен')->get());
    }
}
