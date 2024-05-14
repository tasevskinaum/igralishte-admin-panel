<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'red', 'display_name' => 'Red', 'hex_code' => '#FF835E'],
            ['name' => 'orange', 'display_name' => 'Orange', 'hex_code' => '#FBC26C'],
            ['name' => 'yellow', 'display_name' => 'Yellow', 'hex_code' => '#FCFF81'],
            ['name' => 'green', 'display_name' => 'Green', 'hex_code' => '#B9E5A4'],
            ['name' => 'blue', 'display_name' => 'Blue', 'hex_code' => '#75D7F0'],
            ['name' => 'pink', 'display_name' => 'Pink', 'hex_code' => '#FFDBDB'],
            ['name' => 'purple', 'display_name' => 'Purple', 'hex_code' => '#EA97FF'],
            ['name' => 'gray', 'display_name' => 'Gray', 'hex_code' => '#D9D9D9'],
            ['name' => 'white', 'display_name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'black', 'display_name' => 'Black', 'hex_code' => '#232221'],
        ];

        Color::insert($colors);
    }
}
