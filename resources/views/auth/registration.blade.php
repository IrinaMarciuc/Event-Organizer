@extends('base')
@section('content')
    @include('shared.growl')
    <div class="flex-fill d-flex align-items-center">
        <div class="p-2 flex-grow-1">
            <div class="card w-25 mx-auto">
                <h3 class="card-header text-center">Register User</h3>
                <div class="card-body">
                    <form action="{{ route('user.register') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Name" id="name" class="form-control" name="name"
                                autofocus>
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Email" id="email_address" class="form-control" name="email">
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
                        <div class="d-grid mx-auto mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Sign up</button>
                        </div>
                        <div class="form-group">
                            Already a user? <a href="{{ route('login') }}">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
