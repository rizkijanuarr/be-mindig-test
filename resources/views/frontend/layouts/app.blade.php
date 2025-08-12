<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mindig - Mini Digital')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white">
    @include('frontend.components.announcement')
    @include('frontend.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.footer')

    @include('frontend.components.scripts')
    @stack('scripts')
</body>
</html>
