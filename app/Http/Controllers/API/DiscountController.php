<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Discount\DiscountResource;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        return DiscountResource::collection(Discount::byStatus('Активен')->get());
    }

    public function show(Discount $discount)
    {
        if (!($discount->status->name === 'Активен'))
            return response()
                ->json(['error' => 'Попустот не постои!'], 404);

        return new DiscountResource($discount);
    }
}
