@extends('layouts.auth')

@section('title', 'Verify email')

@section('content')
    <h2>Verify your email address</h2>

    <p>
        Before you continue, please check your email for a verification link.
        If you did not receive the email, we can send another one.
    </p>

    @if (session('status') === 'verification-link-sent')
        <p class="notice">A new verification link has been sent to your email address.</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">Log out</button>
    </form>
@endsection
