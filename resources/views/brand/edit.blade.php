@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-sm-10 col-md-8 col-lg-6">
            <div id="brand-edit" class="d-flex">
                <div>
                    <div class="row header">
                        <div class="col d-flex align-items-center">
                            <div class="go-back-arrow">
                                <a href="{{ route('brands.index') }}">
                                    <i class='bx bx-arrow-back'></i>
                                </a>
                            </div>
                            <div class="title">
                                Бренд
                            </div>
                        </div>
                    </div>
                    <div class="row brand-form">
                        <div class="col ">

                            <form action="{{ route('brands.update', $brand->id) }}" method="post" enctype="multipart/form-data" id="brand-form">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="name">Име на бренд</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}">
                                    @error('name')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="describe">Опис</label>
                                    <textarea name="describe" id="describe" rows="3">{{ old('describe', $brand->description) }}</textarea>
                                    @error('describe')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tags">Ознаки:</label>
                                    <textarea name="tags" id="tags" rows="2">{{ old('tags') ?: implode(', ', $brand->tags->pluck('name')->toArray()) }}</textarea>
                                    @error('tags')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="images">Слики:</label>
                                    <div id="images" class="d-flex flex-wrap">
                                        @foreach($brand->images as $image)
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
                                    @error('old-images.*')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="category-container">
                                    <label for="category">Категорија:</label>
                                    <select name="category" id="category">
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @foreach(old('category', $brand->categories->pluck('id')->toArray()) as $oldCategory) {{ $oldCategory == $category->id ? 'selected' : '' }} @endforeach>
                                            {{ $category->display_name }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('category')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                    @error('category.*')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="status-container">
                                    <select name="status" id="status">
                                        <option selected disabled>Статус</option>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status', $brand->status_id) == $status->id ? 'selected' : '' }}>
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
                                        <a href="{{ route('brands.index') }}">Откажи</a>
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
@vite(['resources/js/brand/create-edit/category-multiple-select.js'])
@vite(['resources/js/brand/create-edit/tags-input.js'])
@vite(['resources/js/brand/create-edit/image-input.js'])

@endsection