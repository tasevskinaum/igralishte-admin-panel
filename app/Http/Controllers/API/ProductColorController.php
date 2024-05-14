<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
    public function index()
    {
        return ColorResource::collection(Color::all());
    }
}
