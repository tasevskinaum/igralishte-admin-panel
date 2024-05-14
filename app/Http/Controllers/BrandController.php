<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreRequest;
use App\Http\Requests\Brand\UpdateRequest;
use App\Models\Brand;
use App\Models\BrandGallery;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ImageKit\ImageKit;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeBrands = Brand::byStatus('Активен')->get();
        $archivedBrands = Brand::byStatus('Архивиран')->get();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $activeBrands = Brand::byStatus('Активен')->where('name', 'like', '%' . $searchTerm . '%')->get();

            $archivedBrands = Brand::byStatus('Архивиран')->where('name', 'like', '%' . $searchTerm . '%')->get();
        }

        return view('brand.index', compact(['activeBrands', 'archivedBrands']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $statuses = Status::all();

        return view('brand.create', compact('categories', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            //Store brand
            $brand = Brand::create([
                'name' => $request->name,
                'description' => $request->describe,
                'status_id' => $request->status,
            ]);

            // store tags
            $tags = array_map('strtolower', array_map('trim', explode(',', $request->tags)));

            $tagIds = [];

            foreach ($tags as $tag) {
                $tagIds[] = Tag::firstOrCreate(['name' => $tag])->id;
            }

            $brand->tags()->attach($tagIds, ['created_at' => now(), 'updated_at' => now()]);

            //store categories
            $brand->categories()->attach($request->category, ['created_at' => now(), 'updated_at' => now()]);

            //store images
            foreach ($request->images as $image) {
                BrandGallery::create([
                    'brand_id' => $brand->id,
                    'image_url' => $this->uploadImage($image)
                ]);
            }

            DB::commit();

            return redirect()
                ->route('brands.index')
                ->with(['success' => "Брендот {$request->name} е креиран."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('brands.index')
                ->with(['error' => "Се случи неочекувана грешка при креирање на запсот. Обидете се повторно!"]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $categories = Category::all();
        $statuses = Status::all();

        return view('brand.edit', compact('categories', 'statuses', 'brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Brand $brand)
    {
        DB::beginTransaction();

        try {
            $brand->name = $request->name;
            $brand->description = $request->describe;
            $brand->status_id = $request->status;

            $brand->update();

            //tags

            $tags = array_map('strtolower', array_map('trim', explode(',', $request->tags)));

            $tagIds = [];

            foreach ($tags as $tag) {
                $tagIds[] = Tag::firstOrCreate(['name' => $tag])->id;
            }

            $brand->tags()->sync($tagIds);

            //categories
            $brand->categories()->sync($request->category);

            //images
            $imagesToDelete = $brand->images->pluck('id')->diff($request->input('old-images'));
            BrandGallery::whereIn('id', $imagesToDelete)->delete();


            if ($request->images) {
                foreach ($request->images as $image) {
                    BrandGallery::create([
                        'brand_id' => $brand->id,
                        'image_url' => $this->uploadImage($image)
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('brands.index')
                ->with(['success' => "Записот успешно е ажуриран."]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('brands.index')
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
            $brand = Brand::find($request->brand);

            if (!$brand) {
                return redirect()
                    ->route('brands.index')
                    ->with(['error' => "Се случи неочекувана грешка при бришење на запсот. Обидете се повторно!"]);
            }

            $brand->tags()->detach();

            $brand->categories()->detach();

            $brand->images()->delete();

            Product::where('brand_id', $brand->id)->update(['brand_id' => null, 'category_id' => null]);

            $brand->delete();

            DB::commit();

            return redirect()
                ->route('brands.index')
                ->with(['success' => "Записот е избришан."]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->route('brands.index')
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
