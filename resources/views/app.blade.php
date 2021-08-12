<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <title>{{ config('app.name') }}</title>

    @production
        <script src="https://cdn.usefathom.com/script.js" data-site="RHQXBEGX" included-domains="utgars-chronicles.app" defer></script>
    @endproduction

    @routes
</head>
<body class="flex flex-col h-full antialiased">
    @inertia

    <div id="portal-modal"></div>

    {{ vite_assets() }}
</body>
</html>
