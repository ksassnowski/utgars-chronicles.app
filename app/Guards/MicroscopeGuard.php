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

namespace App\Guards;

use App\AnonymousPlayer;
use App\MicroscopePlayer;
use Illuminate\Http\Request;

class MicroscopeGuard
{
    public function __invoke(Request $request): MicroscopePlayer
    {
        return $request->user() ?: new AnonymousPlayer(
            $request->session()->getId(),
            $request->session()->get('histories', []),
        );
    }
}
