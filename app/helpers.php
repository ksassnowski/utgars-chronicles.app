<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

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
        return new HtmlString(<<<'HTML'
            <script type="module" src="http://localhost:3000/@vite/client"></script>
            <script type="module" src="http://localhost:3000/resources/js/app.js"></script>
        HTML);
    }

    $manifestJson = \file_get_contents(public_path('build/manifest.json'));

    if ($manifestJson === false) {
        throw new RuntimeException('Unable to load manifest.json');
    }

    $manifest = \json_decode($manifestJson, true);

    return new HtmlString(<<<HTML
        <script type="module" src="/build/{$manifest['resources/js/app.js']['file']}" defer></script>
        <link rel="stylesheet" href="/build/{$manifest['resources/js/app.js']['css'][0]}">
    HTML);
}
