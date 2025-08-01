@extends('layouts.app')

@section('content')
<div class="login-form py-11">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="text-center pb-8">
            <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h2>
            <span class="text-muted font-weight-bold font-size-h4">
                Or <a href="{{ route('register') }}" class="text-primary font-weight-bolder">Create An Account</a>
            </span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="email" name="email" required />
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" required />
        </div>
        <div class="text-center pt-2">
            <button type="submit" class="btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In</button>
        </div>
    </form>
</div>
@endsection
