@extends('layouts.auth')

@section('title', 'Reset password')

@section('content')
    <h2>Reset your password</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email">

        <label for="password">New password</label>
        <input id="password" name="password" type="password" required autocomplete="new-password">

        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">

        <button type="submit">Reset password</button>
    </form>
@endsection
