@extends('layouts.guest')

@section('content')
<div class="container-fluid" id="forgot-password">
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 col-md-6 col-lg-3">
            <div class="form">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email">Е-маил</label>
                        <input type="email" name="email" id="email" name="email" placeholder="Е-маил" value="{{ old('email', $request->email) }}">
                        @error('email')
                        <div class="fdb">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password">Лозинка</label>
                        <input type="password" name="password" id="password" name="password" placeholder="Лозинка" autocomplete="new-password">
                        @error('password')
                        <div class="fdb">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation">Потврди лозинка</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" name="password_confirmation" placeholder="Потврди лозинка" autocomplete="new-password">
                        @error('password_confirmation')
                        <div class="fdb">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <button type="submit">Промени лозинка</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection