<?php declare(strict_types=1);

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Http;

function vite_assets(): HtmlString
{
    $devServerIsRunning = false;

    if (app()->environment('local')) {
        try {
            Http::get('http://localhost:3000');
            $devServerIsRunning = true;
        } catch (Exception) {
        }
    }

    if ($devServerIsRunning) {
        return new HtmlString(<<<HTML
            <script type="module" src="http://localhost:3000/@vite/client"></script>
            <script type="module" src="http://localhost:3000/resources/js/app.js"></script>
        HTML);
    }

    $manifest = json_decode(file_get_contents(
        public_path('build/manifest.json')
    ), true);

    return new HtmlString(<<<HTML
        <script type="module" src="/build/{$manifest['resources/js/app.js']['file']}" defer></script>
        <link rel="stylesheet" href="/build/{$manifest['resources/js/app.js']['css'][0]}">
    HTML);
}
