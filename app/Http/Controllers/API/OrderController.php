<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Order;
use App\Models\ProductGallery;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function getAllOrders(Request $request)
    {
        $orders = $request->user()->orders()->with(['products' => function ($query) {
            $query->withPivot('color_id', 'size_id', 'price', 'quantity');
        }])->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Немате нарачки.',
            ], 404);
        }

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'total_price' => $order->total_price,
                'full_name' => "{$order->firstname} {$order->lastname}",
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'products' => $order->products->map(function ($product) {
                    $productGallery = ProductGallery::where('product_id', $product->id)->first();
                    $color = Color::find($product->pivot->color_id);
                    $size = Size::find($product->pivot->size_id);

                    return [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_image' => $productGallery ? $productGallery->image_url : null,
                        'color' => $color ? $color->hex_code : null,
                        'size' => $size ? $size->name : null,
                        'price' => $product->pivot->price,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => "Нарачки нарачани од корисникот: {$request->user()->firstname} {$request->user()->lastname}",
            'orders' => $formattedOrders,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ], [
            'firstname.required' => 'Внесете име.',
            'lastname.required' => 'Внесете презиме.',
            'phone.required' => 'Внесете телефонски број.',
            'address.required' => 'Внесете ја вашата адреса на живеење.',
            'email.required' => 'Внесете емаил адреса.',
            'email.email' => 'Внесете валидна емаил адреса.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer = $request->user();
            $cartItems = $customer->cart_items;
            $totalPrice = 0;


            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Кошничката е празна!',
                ], 404);
            }

            foreach ($cartItems as $cartItem) {
                $totalPrice += $cartItem->product->getProductPrice() * $cartItem->quantity;
            }

            DB::beginTransaction();

            $order = Order::create([
                'date' => Carbon::now(),
                'total_price' => $totalPrice,
                'customer_id' => $customer->id,
                'is_completed' => false,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email
            ]);

            foreach ($cartItems as $cartItem) {
                $order->products()->attach($cartItem->product_id, [
                    'quantity' => $cartItem->quantity,
                    'color_id' => $cartItem->color_id,
                    'size_id' => $cartItem->size_id,
                    'price' => $cartItem->product->getProductPrice() * $cartItem->quantity
                ]);
            }

            $customer->cart_items()->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
