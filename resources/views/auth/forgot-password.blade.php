@extends('layouts.guest')

@section('content')
<div class="container-fluid" id="forgot-password">
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 col-md-6 col-lg-3">
            <div class="form">
                <span>Ја заборави лозинката? Внесете ја вашата емаил адреса во полето подолу и ние ќе ви испратиме понатамошни упатства за промена на лозинката на вашата емаил адреса.</span>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <input type="email" name="email" id="email" name="email" placeholder="Е-маил">
                        @error('email')
                        <div class="fdb">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <button type="submit">ИСПРАТИ</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection