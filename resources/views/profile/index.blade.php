@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-sm-8 col-md-6 col-lg-3">
            <div id="profile">

                <div class="row">
                    <div class="col p-0">
                        <div class="title">
                            Мој профил
                        </div>
                    </div>
                </div>

                <div class="row image">
                    <div class="col p-0">
                        <div class="image">
                            <img width="30px" src="{{ auth()->user()->profile_picture }}" alt="">
                            <span class="change-photo-btn">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#change-profile-picture-modal">
                                    Промени слика
                                </button>
                            </span>
                            @error('image')
                            <span class="error-fdb">{{ $message }}</span>
                            @enderror


                            <div class="modal fade" id="change-profile-picture-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">Промени слика</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="picture d-flex flex-wrap justify-content-center">
                                                <span>
                                                    <img src="{{ auth()->user()->profile_picture }}" alt="">
                                                    <label for="change-photo-input">
                                                        <i class='bx bx-image-add'></i>
                                                    </label>
                                                </span>
                                                <form id="profile-picture-form" action="{{ route('profile.picture.update') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" name="image" accept="image/*" class="image-input" id="change-photo-input">
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" data-bs-dismiss="modal">Промени</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form">
                    <div class="col p-0">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="name">Име</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}">
                                @error('name')
                                <div class="fdb">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="email">Email адреса</label>
                                <input type="text" id="email" name="email" value="{{ auth()->user()->email }}">
                                @error('email')
                                <div class="fdb">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="phone">Телефонски број</label>
                                <input type="text" id="phone" name="phone" value="{{ auth()->user()->phone }}">
                                @error('phone')
                                <div class="fdb">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <div class="change-password-btn">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#change-password-modal">
                                        Промени лозинка
                                    </button>
                                </div>
                            </div>
                            <div>
                                <button type="submit">Зачувај</button>
                            </div>
                        </form>

                        <div class="modal fade" id="change-password-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Промени лозинка</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="change-pw-form" action="{{ route('password.update') }}" method="post">
                                            @csrf
                                            @method('put')
                                            <div>
                                                <label for="current_password">Сегашна лозинка:</label>
                                                <input type="password" id="current_password" name="current_password">
                                                @error('current_password', 'updatePassword')
                                                <div class="fdb">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="password">Нова лозинка:</label>
                                                <input type="password" id="password" name="password">
                                                @error('password', 'updatePassword')
                                                <div class="fdb">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="password_confirmation">Потврди нова лозинка:</label>
                                                <input type="password" id="password_confirmation" name="password_confirmation">
                                                @error('password_confirmation', 'updatePassword')
                                                <div class="fdb">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" data-bs-dismiss="modal">Промени</button>
                                    </div>
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

@if(session()->has('errors') && session()->get('errors')->hasBag('updatePassword'))
<div id="error-message" data-message="Внесовте неточна лозинка или лозинките не се совпаѓаат !" style="display: none;"></div>
@endif
@endsection

@section('script')

@vite(['resources/js/loader/form-loader.js'])

<script>
    $(document).ready(function() {

        $("#change-profile-picture-modal .modal-footer button[type='submit']").click(() => {
            $("#profile-picture-form").submit();
        })

    });
</script>

<script>
    $(document).ready(function() {
        var originalPhotoUrl = $('#change-profile-picture-modal .picture img').attr('src');

        $('#change-photo-input').change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#change-profile-picture-modal .picture img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        $('#change-profile-picture-modal').on('hidden.bs.modal', function() {
            $('#change-profile-picture-modal .picture img').attr('src', originalPhotoUrl);
            $('#change-photo-input').val(''); // Clear the file input
        });
    });
</script>

<script>
    $(document).ready(function() {

        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');

        if (successMessage || errorMessage) {
            let message = successMessage ? successMessage.getAttribute('data-message') : errorMessage.getAttribute('data-message');
            let icon = successMessage ? 'success' : 'error';

            if (successMessage) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer);
                        toast.addEventListener("mouseleave", Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: icon,
                    title: message
                });
            } else {
                Swal.fire({
                    text: message,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal2-btn-sm'
                    },
                    confirmButtonText: 'Затвори',
                    icon: icon
                });
            }
        }
    });
</script>

<script>
    $(document).ready(function() {

        $("#change-password-modal .modal-footer button[type='submit']").click(() => {
            $("#change-pw-form").submit();
        })

    });
</script>
@endsection