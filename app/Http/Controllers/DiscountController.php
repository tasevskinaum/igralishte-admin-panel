<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discount\StoreRequest;
use App\Models\Discount;
use App\Models\DiscountCategory;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeDiscounts = Discount::byStatus('Активен')->get();
        $archivedDiscounts = Discount::byStatus('Архивиран')->get();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $activeDiscounts = $activeDiscounts->filter(function ($discount) use ($searchTerm) {
                return stripos($discount->name, $searchTerm) !== false;
            });

            $archivedDiscounts = $archivedDiscounts->filter(function ($discount) use ($searchTerm) {
                return stripos($discount->name, $searchTerm) !== false;
            });
        }

        return view('discount.index', compact('activeDiscounts', 'archivedDiscounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Status::all();
        $categories = DiscountCategory::all();

        return view('discount.create', compact('statuses', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $discount = Discount::create([
                'name' => $request->name,
                'percent' => $request->discount,
                'discount_category_id' => $request->category,
                'status_id' => $request->status
            ]);

            // Set or remove discount
            $status = Status::find($request->status);

            if ($status && $status->name === "Активен") {
                $products_to_set_discount = array_map(
                    function ($element) {
                        return str_replace('#', '', trim($element));
                    },
                    explode(',', $request->set_discount_on)
                );

                Product::whereIn('id', $products_to_set_discount)->update(['discount_id' => $discount->id]);
            }

            DB::commit();

            return redirect()
                ->route('discounts.index')
                ->with(['success' => "Попустот {$request->name} е креиран."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('discounts.index')
                ->with(['error' => "Се случи неочекувана грешка при креирање на запсот. Обидете се повторно!"]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        $statuses = Status::all();
        $categories = DiscountCategory::all();

        return view('discount.edit', compact('statuses', 'categories', 'discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Discount $discount)
    {
        DB::beginTransaction();

        try {
            $discount->name = $request->name;
            $discount->percent = $request->discount;
            $discount->discount_category_id = $request->category;
            $discount->status_id = $request->status;

            // Set or remove discount
            $status = Status::find($request->status);

            if ($status && $status->name === "Архивиран") {
                Product::where('discount_id', $discount->id)->update(['discount_id' => null]);
            } else {
                $products_to_set_discount = array_map(
                    function ($element) {
                        return str_replace('#', '', trim($element));
                    },
                    explode(',', $request->set_discount_on)
                );

                Product::whereIn('id', $products_to_set_discount)->update(['discount_id' => $discount->id]);
            }

            $discount->save();

            DB::commit();

            return redirect()
                ->route('discounts.index')
                ->with(['success' => "Записот успешно е ажуриран."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('discounts.index')
                ->with(['error' => "Се случи неочекувана грешка при ажурирање на запсот. Обидете се повторно!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $discount = Discount::find(($request->discount));

            if (!$discount) {
                return redirect()
                    ->route('discounts.index')
                    ->with(['error' => "Се случи неочекувана грешка при бришење на запсот. Обидете се повторно!"]);
            }

            Product::where('discount_id', $discount->id)->update(['discount_id' => null]);

            $discount->delete();

            DB::commit();

            return redirect()
                ->route('discounts.index')
                ->with(['success' => "Записот е избришан."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('discounts.index')
                ->with(['error' => "Се случи неочекувана грешка при бришење на запсот. Обидете се повторно!"]);
        }
    }
}
