<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <title>{{ config('app.name') }} - Play Microscope Online</title>

    <script src="https://cdn.usefathom.com/script.js" data-site="RHQXBEGX" included-domains="utgars-chronicles.app" defer></script>
</head>
<body class="flex flex-col h-full antialiased">
    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="page-footer mt-20 pt-8 text-xs text-center pb-4 relative text-gray-500">
        Created in Munich by <a href="https://twitter.com/@warsh33p" target="_blank" rel="noreferrer noopener">@warsh33p</a> &mdash; &copy; {{ date('Y') }}
    </footer>
</body>
</html>
