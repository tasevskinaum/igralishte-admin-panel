<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\StatusResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => new StatusResource($this->status),
            'gallery' => GalleryResource::collection($this->images),
            'tags' => TagResource::collection($this->tags),
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
