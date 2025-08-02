@extends('layouts.app')

@section('content')
<div class="login-form pt-11">
    {{-- Hiển thị lỗi validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hiển thị thông báo status --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="text-center pb-8">
            <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign Up</h2>
            <p class="text-muted font-weight-bold font-size-h4">Enter your details to create your account</p>
        </div>

        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="text" 
                   placeholder="Username" 
                   name="username" 
                   value="{{ old('username') }}" 
                   required />
        </div>

        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="text" 
                   placeholder="Fullname (Optional)" 
                   name="fullname" 
                   value="{{ old('fullname') }}" />
        </div>

        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="email" 
                   placeholder="Email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required />
        </div>

        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="password" 
                   placeholder="Password" 
                   name="password" 
                   required />
        </div>

        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="password" 
                   placeholder="Confirm Password" 
                   name="password_confirmation" 
                   required />
        </div>

        <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">
                Register
            </button>
            <a href="{{ route('login') }}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection


