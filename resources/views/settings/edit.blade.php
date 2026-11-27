@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <h2>Settings</h2>

    <h3>Profile</h3>

    @if ($errors->updateProfileInformation->any())
        <ul>
            @foreach ($errors->updateProfileInformation->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('user-profile-information.update') }}">
        @csrf
        @method('PUT')

        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required>

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>

        <button type="submit">Save profile</button>
    </form>

    <h3>Password</h3>

    @if ($errors->updatePassword->any())
        <ul>
            @foreach ($errors->updatePassword->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('user-password.update') }}">
        @csrf
        @method('PUT')

        <label for="current_password">Current password</label>
        <input id="current_password" name="current_password" type="password" required autocomplete="current-password">

        <label for="password">New password</label>
        <input id="password" name="password" type="password" required autocomplete="new-password">

        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">

        <button type="submit">Update password</button>
    </form>
@endsection
