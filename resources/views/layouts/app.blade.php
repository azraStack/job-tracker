<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Job Tracker')</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <header>
        <h1>Job Tracker</h1>
        <nav>
            <a href="{{ route('applications.index') }}">Applications</a>
            <a href="{{ route('companies.index') }}">Companies</a>
            @auth
                <a href="{{ route('settings.edit') }}">Settings</a>
            @endauth
        </nav>

        @auth
            <p>
                Signed in as <strong>{{ auth()->user()->name }}</strong>
                ({{ auth()->user()->email }})
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Log out</button>
            </form>
        @endauth
    </header>

    <main>
        @if (session('status'))
            <p class="notice">{{ session('status') }}</p>
        @endif

        @yield('content')
    </main>
</body>
</html>