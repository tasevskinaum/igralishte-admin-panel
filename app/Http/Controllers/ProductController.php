<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Size;
use App\Models\Status;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ImageKit\ImageKit;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $products->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $products->paginate(9);

        return view('product.index', compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeStatus = Status::where('name', 'Активен')->first();

        $statuses = Status::all();
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        $discounts = $activeStatus->discounts;

        return view('product.create', compact(['statuses', 'sizes', 'colors', 'categories', 'discounts']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            // SIZE PREP
            $sizes = Size::pluck('name')->toArray();

            $sizeQuantities = [];

            foreach ($sizes as $size) {
                $quantityKey = 'quantity' . $size;
                $sizeQuantities[$size] = $request->$quantityKey ?? 0;
            }

            //------------------------------------------------------------

            // Store PRODUCT
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'size_advice' => $request->size_advice,
                'maintenance_guidelines' => $request->maintenance_guidelines,
                'discount_id' => $request->discount,
                'brand_id' => $request->brand,
                'category_id' => $request->category,
                'in_stock' => array_sum($sizeQuantities),
                'status_id' => $request->status
            ]);


            // Store IMAGES
            foreach ($request->images as $image) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image_url' => $this->uploadImage($image)
                ]);
            }

            // Store SIZES
            $sizeData = [];

            foreach ($sizeQuantities as $size => $quantity) {
                $sizeData[Size::where('name', $size)->first()->id] = ['quantity' => $quantity];
            }

            $product->sizes()->sync($sizeData);

            //Store TAGS
            $tags = array_map('strtolower', array_map('trim', explode(',', $request->tags)));

            $tagIds = [];

            foreach ($tags as $tag) {
                $tagIds[] = Tag::firstOrCreate(['name' => $tag])->id;
            }

            $product->tags()->attach($tagIds, ['created_at' => now(), 'updated_at' => now()]);

            // Store COLORS
            $product->colors()->attach($request->colors, ['created_at' => now(), 'updated_at' => now()]);

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with(['success' => "Продуктот {$request->name} е креиран."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('products.index')
                ->with(['error' => "Се случи неочекувана грешка при креирање на запсот. Обидете се повторно!"]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $activeStatus = Status::where('name', 'Активен')->first();

        $statuses = Status::all();
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        $discounts = $activeStatus->discounts;

        return view('product.edit', compact(['product', 'statuses', 'sizes', 'colors', 'categories', 'discounts']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product)
    {

        DB::beginTransaction();

        try {
            // SIZE PREP
            $sizes = Size::pluck('name')->toArray();

            $sizeQuantities = [];

            foreach ($sizes as $size) {
                $quantityKey = 'quantity' . $size;
                $sizeQuantities[$size] = $request->$quantityKey ?? 0;
            }

            //------------------------------------------------------------

            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->size_advice = $request->size_advice;
            $product->maintenance_guidelines = $request->maintenance_guidelines;
            $product->discount_id = $request->discount;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->in_stock = array_sum($sizeQuantities);
            $product->status_id = $request->status;

            $product->update();

            // update images
            $imagesToDelete = $product->images->pluck('id')->diff($request->input('old-images'));
            ProductGallery::whereIn('id', $imagesToDelete)->delete();

            if ($request->images) {
                foreach ($request->images as $image) {
                    ProductGallery::create([
                        'product_id' => $product->id,
                        'image_url' => $this->uploadImage($image)
                    ]);
                }
            }

            // update tags
            $tags = array_map('strtolower', array_map('trim', explode(',', $request->tags)));

            $tagIds = [];

            foreach ($tags as $tag) {
                $tagIds[] = Tag::firstOrCreate(['name' => $tag])->id;
            }

            $product->tags()->sync($tagIds);

            //update colors
            $product->colors()->sync($request->colors);


            //update sizes

            $sizeData = [];

            foreach ($sizeQuantities as $size => $quantity) {
                $sizeData[Size::where('name', $size)->first()->id] = ['quantity' => $quantity];
            }

            $product->sizes()->sync($sizeData);


            DB::commit();

            return redirect()
                ->route('products.index')
                ->with(['success' => "Записот успешно е ажуриран."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('products.index')
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
            $product = Product::find($request->product);

            if (!$product) {
                return redirect()
                    ->route('products.index')
                    ->with(['error' => "Се случи неочекувана грешка при бришење на запсот. Обидете се повторно!"]);
            }

            $product->sizes()->detach();

            $product->colors()->detach();

            $product->tags()->detach();

            $product->images()->delete();

            $product->delete();

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with(['success' => "Записот е избришан."]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->route('products.index')
                ->with(['error' => "Се случи неочекувана грешка при бришење на запсот. Обидете се повторно!"]);
        }
    }

    private function uploadImage($image)
    {
        $imageKit = new ImageKit(
            'public_HqvXchqCR3L08wnPnLXHdgNDhk4=',
            'private_5xIEDMhXzw+5XKstdR4q/WOqiSQ=',
            'https://ik.imagekit.io/lztd93pns',
        );

        if ($image) {
            $fileType = mime_content_type($image->path());

            $fileData = [
                'file' => 'data:' . $fileType . ';base64,' . base64_encode(file_get_contents($image->path())),
                'fileName' => $image->getClientOriginalName(),
                'folder' => 'Igralishte',
            ];

            $uploadedFile = $imageKit->uploadFile($fileData);

            if ($uploadedFile->result->url) {
                return $uploadedFile->result->url;
            }
        }

        return null;
    }
}
