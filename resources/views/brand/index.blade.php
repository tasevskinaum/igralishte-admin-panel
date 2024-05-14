@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-sm-8 col-md-6 col-lg-3">
            <div id="brands">
                <div id="search-row" class="row search align-items-center mb-3">
                    <div class="col">
                        <div>
                            <form action="" method="" class="d-flex align-items-center">
                                <input type="text" id="search" name="search" class="w-100" placeholder="Пребарувај...">
                                <button type="submit">
                                    <i class='bx bx-search'></i>
                                </button>
                                <i class='bx bx-chevron-down'></i>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row add mb-4">
                    <div class="col p-0">
                        <div class="d-flex align-items-center justify-content-end ">
                            Додај нов бренд
                            <a href="{{ route('brands.create') }}">
                                <i class='bx bx-plus add-btn'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row brands">
                    <div class="col p-0">
                        <div id="active-brands">
                            <div class="title">
                                Активни
                            </div>

                            @foreach($activeBrands as $brand)
                            <div class="brand active">
                                <div class="brand-name">{{ $brand->name }}</div>
                                <div class="d-flex align-items-center actions">
                                    <a href="{{ route('brands.edit', $brand->id) }}" class="edit">
                                        <i class='bx bx-edit edit-btn'></i>
                                    </a>
                                    <button type="button" class="delete-brand-btn" value="{{ $brand->id }}" data-bs-toggle="modal" data-bs-target="#brand-delete-modal">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div id="inactive-brands">
                            <div class="title">
                                Архива
                            </div>
                            @foreach($archivedBrands as $brand)
                            <div class="brand inactive">
                                <div class="brand-name">{{ $brand->name }}</div>
                                <div class="d-flex align-items-center actions">
                                    <a href="{{ route('brands.edit', $brand->id) }}" class="edit">
                                        <i class='bx bx-edit edit-btn'></i>
                                    </a>
                                    <button type="button" class="delete-brand-btn" value="{{ $brand->id }}" data-bs-toggle="modal" data-bs-target="#brand-delete-modal">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="modal fade" id="brand-delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('brands.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="brand" id="delete-brand-input">
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

@vite(['resources/js/brand/index/main.js' , 'resources/js/alert/success-error-sweetalert.js'])
@vite(['resources/js/brand/search.js'])

@endsection