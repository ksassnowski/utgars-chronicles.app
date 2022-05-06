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

namespace App\Http\Middleware;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return \array_merge(parent::share($request), [
            'environment' => static fn () => app()->environment(),
            'auth' => static function () use ($request) {
                return [
                    'user' => !$request->routeIs('invitation.accept.show-form') && Auth::user() ? [
                        /** @phpstan-ignore-next-line  */
                        'id' => Auth::user()->id,
                        /** @phpstan-ignore-next-line  */
                        'name' => Auth::user()->name,
                        /** @phpstan-ignore-next-line  */
                        'email' => Auth::user()->email,
                    ] : null,
                ];
            },
            'errors' => static function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
            'flash' => static function () {
                return [
                    'success' => Session::get('success'),
                    'error' => Session::get('error'),
                ];
            },
        ]);
    }
}
