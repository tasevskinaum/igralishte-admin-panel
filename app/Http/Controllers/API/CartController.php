<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getCartItems(Request $request)
    {
        $cartItems = $request->user()->cart_items()
            ->with('product', 'size', 'color')
            ->get()
            ->map(function ($cartItem) {
                unset($cartItem->customer_id);
                return $cartItem;
            });

        return response()->json([
            'status' => true,
            'message' => 'Ставките од кошничката се успешно преземени.',
            'cart_items' => $cartItems,
        ]);
    }

    public function addItemToCart(Request $request, Product $product)
    {
        if (!($product->status->name === 'Активен'))
            return response()
                ->json(['error' => 'Продуктот не постои!'], 404);

        $validator = Validator::make($request->all(), [
            'size' => [
                'required',
                function ($attribute, $value, $fail) use ($product) {
                    if (!$product->sizes()->where('size_id', $value)->exists()) {
                        $fail('Продуктот го нема на залиха во избраната величина.');
                    }
                },
            ],
            'color' => [
                'required',
                function ($attribute, $value, $fail) use ($product) {
                    if (!$product->colors()->where('color_id', $value)->exists()) {
                        $fail('Продуктот го нема на залиха во избраната боја.');
                    }
                },
            ],
            'quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($product, $request) {

                    $pivotQuantity = $product->sizes()->where('size_id', $request->size)->first()->pivot->quantity ?? 0;
                    $existingCartItem = $request->user()->cart_items()
                        ->where('product_id', $product->id)
                        ->where('size_id', $request->size)
                        ->where('color_id', $request->color)
                        ->first();

                    if ($existingCartItem) {
                        $totalQuantity = $existingCartItem->quantity + $value;
                        if ($pivotQuantity < $totalQuantity) {
                            $fail("Избраната количина ги надминува достапните залихи за оваа големина. На залиха: {$pivotQuantity}");
                        }
                    } else {
                        if ($pivotQuantity < $value) {
                            $fail("Избраната количина ги надминува достапните залихи за оваа големина. На залиха: {$pivotQuantity}");
                        }
                    }
                },
            ]
        ], [
            'size.required' => 'Изберете големина.',
            'color.required' => 'Изберете боја.',
            'quantity.required' => 'Внесете количина.',
            'quantity.numeric' => 'Количината мора да биде нумеричка.',
            'quantity.min' => 'Количината мора да биде повеќе од 0.',
            'quantity.validation' => 'Избраната количина ги надминува достапните залихи за оваа големина. На залиха: :pivotQuantity',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingCartItem = $request->user()->cart_items()->where('product_id', $product->id)
            ->where('size_id', $request->size)
            ->where('color_id', $request->color)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();

            return response()->json([
                'status' => true,
                'message' => 'Продуктот е додаден во кошничката.',
                'cart_item' => [
                    'id' => $existingCartItem->id,
                    'product' => $existingCartItem->product,
                    'size' => $existingCartItem->size,
                    'quantity' => $existingCartItem->quantity,
                    'color' => $existingCartItem->color,
                ]
            ]);
        } else {
            $cartItem = Cart::create([
                'product_id' => $product->id,
                'color_id' => $request->color,
                'size_id' => $request->size,
                'quantity' => $request->quantity,
                'customer_id' => $request->user()->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Продуктот е додаден во кошничката.',
                'cart_item' => [
                    'id' => $cartItem->id,
                    'product' => $cartItem->product,
                    'size' => $cartItem->size,
                    'quantity' => $cartItem->quantity,
                    'color' => $cartItem->color,
                ]
            ]);
        }
    }

    public function deleteCartItem(Request $request, Cart $cartItem)
    {
        if ($cartItem->customer_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Не сте овластени да ја избришете оваа ставка од кошничката.',
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'Продуктот е отстранет од кошничката.',
        ]);
    }

    public function clearCart(Request $request)
    {
        $request->user()->cart_items()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Кошничката е празна!'
        ]);
    }
}
