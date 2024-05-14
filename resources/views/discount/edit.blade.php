@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-sm-10 col-md-8 col-lg-6">
            <div id="discount-edit" class="d-flex">
                <div class="">
                    <div class="row header">
                        <div class="col d-flex align-items-center">
                            <div class="go-back-arrow">
                                <a href="{{ route('discounts.index') }}">
                                    <i class='bx bx-arrow-back'></i>
                                </a>
                            </div>
                            <div class="title">
                                Попуст/Промо код
                            </div>
                        </div>
                    </div>

                    <div class="row product-form">
                        <div class="col ">

                            <form action="{{ route('discounts.update', $discount->id) }}" method="post" enctype="multipart/form-data" id="product-form">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="name">Име на попуст / промо код</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $discount->name) }}">
                                    @error('name')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discount">Попуст</label>
                                    <input type="text" id="discount" name="discount" value="{{ old('discount', $discount->percent) }}">
                                    @error('discount')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="category">Категорија:</label>
                                    <select name="category" id="category">
                                        <option selected disabled>Одбери</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category', $discount->discount_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->display_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="set_discount_on">Постави попуст на:</label>
                                    <input type="text" id="set_discount_on" name="set_discount_on" value="{{ old('set_discount_on') }}">
                                    @error('set_discount_on')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="status-container">
                                    <select name="status" id="status">
                                        <option selected disabled>Статус</option>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status', $discount->status_id) == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                                        @endforeach

                                    </select>
                                    @error('status')
                                    <span class="error-fdb">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <div class="d-flex align-items-center footer">
                                        <button type="submit">Зачувај</button>
                                        <a href="{{ route('discounts.index') }}">Откажи</a>
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
@vite(['resources/js/discount/create-edit/main.js'])
@vite(['resources/js/loader/form-loader.js'])

@endsection