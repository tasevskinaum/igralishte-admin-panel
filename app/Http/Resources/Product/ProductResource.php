<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discountPrice = $this->discount?->percent
            ? $this->price - ($this->price * $this->discount->percent / 100)
            : null;

        return [
            'id' => $this->id,
            'status' => new StatusResource($this->status),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price . ' ' . 'ден.',
            'discount' => new DiscountResource($this->discount),
            'discounted_price' => $discountPrice !== null ? $discountPrice . ' ден.' : null,
            'sizes' => SizeResource::collection($this->sizes),
            'size_advice' => $this->size_advice,
            'colors' => ColorResource::collection($this->colors),
            'maintenance_guidelines' => $this->maintenance_guidelines,
            'tags' => TagResource::collection($this->tags),
            'gallery' => GalleryResource::collection($this->images),
            'brand' => new BrandResource($this->brand),
            'category' => new CategoryResource($this->category),
            'created_at' => $this->created_at
        ];
    }
}
