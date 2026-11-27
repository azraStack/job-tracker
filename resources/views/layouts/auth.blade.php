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
        <h1><a href="{{ url('/') }}">Job Tracker</a></h1>
    </header>

    <main>
        @if (session('status'))
            <p class="notice">{{ session('status') }}</p>
        @endif

        <article>
            @yield('content')
        </article>
    </main>
</body>
</html>
