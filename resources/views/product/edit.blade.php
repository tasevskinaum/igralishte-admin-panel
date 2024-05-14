@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-sm-10 col-md-8 col-lg-6">
            <div id="product-edit" class="d-flex">
                <div>
                    <div class="row header">
                        <div class="col d-flex align-items-center">
                            <div class="go-back-arrow">
                                <a href="{{ route('products.index') }}">
                                    <i class='bx bx-arrow-back'></i>
                                </a>
                            </div>
                            <div class="title">
                                Продукт
                            </div>
                        </div>
                    </div>
                    <div class="row product-form">
                        <div class="col ">

                            <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data" id="product-form">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="name">Име на продукт</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}">
                                    @error('name')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description">Опис</label>
                                    <textarea name="description" id="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="">Цена</label>
                                    <input type="text" id="price" name="price" value="{{ old('price', $product->price) }}">
                                    @error('price')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="sizes-container">
                                    <div class="d-flex align-items-center">
                                        <label for="sizes">Величина:</label>
                                        <div id="sizes" class="d-flex">
                                            @foreach($sizes as $size)
                                            <span>{{ strtoupper(str_replace(' ', '', $size->name)) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="">Количина:</label>
                                        @foreach($sizes as $size)
                                        <div id="size-{{ strtoupper(str_replace(' ', '', $size->name)) }}" class="size-inputs">
                                            <input type="number" name="quantity{{ strtoupper(str_replace(' ', '', $size->name)) }}" value="{{ old('quantity' . strtoupper(str_replace(' ', '', $size->name)), $product->sizes->where('name', strtoupper(str_replace(' ', '', $size->name)))->first()->pivot->quantity) }}" min="0">
                                            <button class="quantity-control" data-size="{{ strtoupper(str_replace(' ', '', $size->name)) }}" data-change="-1"><i class='bx bx-minus'></i></button>
                                            <span class="quantity-display"></span>
                                            <button class="quantity-control" data-size="{{ strtoupper(str_replace(' ', '', $size->name)) }}" data-change="1"><i class='bx bx-plus'></i></button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('quantityXS')
                                <span class="error-fdb">{{ $message }}</span>
                                @enderror
                                @error('quantityS')
                                <span class="error-fdb">{{ $message }}</span>
                                @enderror
                                @error('quantityM')
                                <span class="error-fdb">{{ $message }}</span>
                                @enderror
                                @error('quantityL')
                                <span class="error-fdb">{{ $message }}</span>
                                @enderror
                                @error('quantityXL')
                                <span class="error-fdb">{{ $message }}</span>
                                @enderror

                                <div>
                                    <label for="size_advice">Совет за величинa:</label>
                                    <textarea name="size_advice" id="size_advice" rows="3">{{ old('size_advice', $product->size_advice) }}</textarea>
                                    @error('size_advice')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="colors">
                                    <label for="">Боја:</label>
                                    <div class="d-flex flex-wrap">
                                        @foreach($colors as $color)
                                        <label for="{{ $color->name }}" @class(['active'=> in_array($color->id, old('colors', (array) optional(optional($product->colors()->where('colors.id', $color->id)->first())->pivot)->color_id))])
                                            @style(["background-color: $color->hex_code", 'border: 0.17px solid #000000' => strpos($color->hex_code, '#FFF') === 0])>
                                        </label>

                                        <input type="checkbox" name="colors[]" id="{{ $color->name }}" value="{{ $color->id }}" {{ in_array($color->id, old('colors', (array) optional(optional($product->colors()->where('colors.id', $color->id)->first())->pivot)->color_id)) ? 'checked' : '' }}>

                                        @endforeach
                                    </div>
                                    @error('colors')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                    @error('colors.*')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="maintenance_guidelines">Насоки за одржување:</label>
                                    <textarea name="maintenance_guidelines" id="maintenance_guidelines" rows="3">{{ old('maintenance_guidelines', $product->maintenance_guidelines) }}</textarea>
                                    @error('maintenance_guidelines')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tags">Ознаки:</label>
                                    <textarea name="tags" id="tags" rows="2">{{ old('tags') ?: implode(', ', $product->tags->pluck('name')->toArray()) }}</textarea>
                                    @error('tags')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="images">Слики:</label>
                                    <div id="images" class="d-flex flex-wrap">
                                        @foreach($product->images as $image)
                                        <label for="old-image-{{ $image->id }}" class="d-flex justify-content-center align-items-center">
                                            <img src="{{ $image->image_url }}" alt="">
                                        </label>
                                        <input type="text" name="old-images[]" class="image-input" id="old-image-{{ $image->id }}" value="{{ $image->id }}">
                                        @endforeach
                                        <label for="input-1" class="d-flex justify-content-center align-items-center">
                                            <i class='bx bx-plus'></i>
                                        </label>
                                        <input type="file" name="images[]" accept="image/*" class="image-input" id="input-1">
                                    </div>
                                    @error('images')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                    @error('images.*')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="category-container">
                                    <label for="category">Категорија:</label>
                                    <select name="category" id="category">
                                        <option selected disabled>Одбери</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == old('category', $product->category_id) ? 'selected' : '' }}>{{ $category->display_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="brand">Бренд:</label>
                                    <select name="brand" id="brand">
                                        <option selected disabled>Одбери</option>
                                        @foreach(optional(optional(App\Models\Category::find($product->category_id))->withActiveBrands())->find($product->category_id)->brands ?? [] as $brand)
                                        <option value="{{ $brand->id }}" {{ $brand->id == old('brand', $product->brand_id) ? 'selected' : '' }}> {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <div class="d-flex align-items-center">
                                        <label for="discount">Додај попуст</label>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#discount-modal">
                                            <i class='bx bx-plus add-btn'></i>
                                        </button>
                                    </div>
                                    @error('discount')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror

                                    <div class="modal fade" id="discount-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title">Додај попуст</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="discount">Попуст:</label>
                                                    <select name="discount" id="discount">
                                                        <option selected value="" @style(["color: #c2c2c2"])>Одбери</option>
                                                        @foreach($discounts as $discount)
                                                        <option value="{{ $discount->id }}" {{ $discount->id == old('discount', $product->discount_id) ? 'selected' : '' }}>{{ $discount->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" data-bs-dismiss="modal">Зачувај</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="status-container">
                                    <select name="status" id="status">
                                        <option selected disabled>Статус</option>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status', $product->status_id) == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <div class="d-flex align-items-center footer">
                                        <button type="submit">Зачувај</button>
                                        <a href="{{ route('products.index') }}">Откажи</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@vite(['resources/js/loader/form-loader.js'])
@vite(['resources/js/product/create-edit/tags.js'])
@vite(['resources/js/product/create-edit/brand.js'])
@vite(['resources/js/product/create-edit/size.js'])
@vite(['resources/js/product/create-edit/color.js'])
@vite(['resources/js/product/create-edit/image.js'])


@endsection