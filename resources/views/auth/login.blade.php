@extends('layouts.guest')

@section('content')
<div class="login-page ">
    <div class="inner h-100 position-relative">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col col-sm-8 col-md-6 col-lg-3 h-100 d-flex flex-column justify-content-between">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <img class="img-fluid" src="https://ik.imagekit.io/lztd93pns/Igralishte/92135cb1b1c427b01ab1a953ca98ab20.png?updatedAt=1708479885401" alt="">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="mb-2">Email адреса</label>
                                    <input type="text" name="email" id="email" placeholder="igralistesk@gmail.com" value="{{ old('email') }}">
                                    @error('email')
                                    <div class="fdb">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="" class="mb-2">Лозинка</label>
                                    <input type="password" name="password" id="password" placeholder="***********">
                                </div>
                                <div class="mb-3">
                                    <a href="{{ route('password.request') }}" class="forgot-password">Ја заборави лозинката?</a>
                                </div>
                                <div>
                                    <button type="submit">Логирај се</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer position-absolute text-center bottom-0 ">
            Сите права задржани @ Игралиште Скопје
        </div>
    </div>

</div>
@endsection