<?php

namespace Database\Seeders;

use App\Models\DiscountCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Percentage Discount',
            'Overstock Sale',
            'Seasonal Discount'
        ];

        foreach ($categories as $category) {
            DiscountCategory::create([
                'name' => strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $category))),
                'display_name' => $category
            ]);
        }
    }
}
