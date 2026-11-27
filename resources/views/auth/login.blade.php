@extends('layouts.auth')

@section('title', 'Log in')

@section('content')
    <h2>Log in to your account</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">

        <label>
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            Remember me
        </label>

        <button type="submit">Log in</button>
    </form>

    <p>
        <a href="{{ route('password.request') }}">Forgot your password?</a>
    </p>

    <p>
        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
    </p>
@endsection
