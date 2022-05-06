<?php

declare(strict_types=1);

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
