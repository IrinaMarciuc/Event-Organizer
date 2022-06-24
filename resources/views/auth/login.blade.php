@extends('base')
@section('content')
    @include('shared.growl')
    <div class="flex-fill d-flex align-items-center">
        <div class="p-2 flex-grow-1">
            <div class="card w-25 mx-auto">
                <h3 class="card-header text-center">Login</h3>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Email" id="email" class="form-control" name="email">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" placeholder="Password" id="password" class="form-control"
                                name="password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="d-grid mx-auto mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Signin</button>
                        </div>
                        <div class="form-group">
                            New user? <a href="{{ route('register') }}">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
