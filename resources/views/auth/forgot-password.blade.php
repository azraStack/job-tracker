@extends('layouts.auth')

@section('title', 'Forgot password')

@section('content')
    <h2>Forgot your password?</h2>

    <p>Enter your email address and we will send you a password reset link.</p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">

        <button type="submit">Email password reset link</button>
    </form>

    <p>
        <a href="{{ route('login') }}">Back to log in</a>
    </p>
@endsection
