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
    <script src="{{ mix('js/app.js') }}" defer></script>

    <title>{{ config('app.name') }}</title>

    <script src="https://cdn.usefathom.com/script.js" data-site="RHQXBEGX" defer></script>
    @routes
</head>
<body class="flex flex-col h-full antialiased">
    @inertia
</body>
</html>
