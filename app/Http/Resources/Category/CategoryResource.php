<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = $this->products->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'products' => $products
        ];
    }
}
