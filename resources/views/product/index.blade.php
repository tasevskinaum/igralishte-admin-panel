@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-sm-8 col-md-6 col-lg-3">
            <div id="products">
                <div id="search-row" class="row search align-items-center mb-3">
                    <div class="col">
                        <div>
                            <form action="{{ route('products.index') }}" method="" class="d-flex align-items-center">
                                <input type="text" id="search" name="search" class="w-100" placeholder="Пребарувај...">
                                <button type="submit">
                                    <i class='bx bx-search'></i>
                                </button>
                                <i class='bx bx-chevron-down'></i>
                            </form>
                        </div>
                        <i class='bx bxs-grid-alt'></i>
                        <i class='bx bx-menu'></i>
                    </div>
                </div>

                <div class="row add mb-4">
                    <div class="col p-0">
                        <div class="d-flex align-items-center justify-content-end ">
                            Додај нов продукт
                            <a href="{{ route('products.create') }}">
                                <i class='bx bx-plus add-btn'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row products">
                    <div class="col p-0">

                        <div id="product-list-view">
                            @foreach($products as $product)
                            <div @class(['product' , 'inactive'=> $product->status->name === 'Архивиран' ])>
                                <div class="product-code">{{ $product->id }}</div>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="d-flex align-items-center actions">
                                    <a href="{{ route('products.edit', $product->id) }}" class="edit">
                                        <i class='bx bx-edit edit-btn'></i>
                                    </a>
                                    <button type="button" class="delete-product-btn" value="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#product-delete-modal">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach

                            <div class="modal fade" id="product-delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="product" id="delete-product-input">
                                                <svg style="color: pink" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                                Дали сте сигурни дека сакате да го избришете записот?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" data-bs-dismiss="modal">Да, избриши</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="product-gallery-view" class="">
                            @foreach($products as $product)
                            <div class="product">
                                <div class="header d-flex align-items-center justify-content-between">
                                    <span>
                                        @if($product->in_stock == 1)
                                        <span>*само 1 парче</span>
                                        @elseif($product->in_stock > 1)
                                        <span>* {{ $product->in_stock }} парчиња</span>
                                        @endif
                                    </span>
                                    @if($product->in_stock == 0)
                                    <span>Продадено</span>
                                    @endif
                                </div>
                                <div class="main">
                                    <div id="gallery-slider-{{ $product->id }}" class="carousel slide">
                                        <div class="carousel-inner">
                                            @foreach($product->images as $key => $image)
                                            <div @class(['carousel-item', 'active'=> $key == 0])>
                                                <img class="img-fluid" src="{{ $image->image_url }}" alt="">
                                            </div>
                                            @endforeach
                                            <button class="carousel-control-prev" type="button" data-bs-target="#gallery-slider-{{ $product->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#gallery-slider-{{ $product->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <div class="product-name product-code d-flex align-items-center justify-content-between">
                                        <span class="name">{{ $product->name }}</span>
                                        <span class="code">{{ $product->id }}</span>
                                    </div>
                                    <div class="colors">
                                        Боја:
                                        @foreach($product->colors as $color)
                                        <span class="color" @style(["background-color: $color->hex_code"])></span>
                                        @endforeach
                                    </div>
                                    <div class="sizes price d-flex align-items-center justify-content-between">
                                        <div class="size">
                                            Величина:
                                            @foreach($product->sizes as $size)
                                            @if ($size->pivot->quantity)
                                            <span>{{ $size->name }}{{ !$loop->last ? ',' : '' }}</span>
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="price d-flex flex-wrap align-items-center">
                                            Цена:&nbsp;&nbsp;
                                            <span class="d-inline-block">
                                                <span class="d-flex flex-column align-items-center">
                                                    @if($product->discount_id)
                                                    <span style="text-decoration:line-through; color:#686b6e;">{{ $product->price }} ден.</span>
                                                    <span>{{ $product->price - ( $product->price * ($product->discount->percent/100) ) }} ден.</span>
                                                    @else
                                                    <span>{{ $product->price }} ден.</span>
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div>
                            {{ $products->onEachSide(1)->links('pagination.cstm-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@if(session()->has('success'))
<div id="success-message" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

@if(session()->has('error'))
<div id="error-message" data-message="{{ session('error') }}" style="display: none;"></div>
@endif
@endsection

@section('script')

@vite(['resources/js/alert/success-error-sweetalert.js'])
@vite(['resources/js/product/index/deleteProduct.js'])
@vite(['resources/js/product/index/toggleView.js'])
@vite(['resources/js/product/search.js'])

@endsection