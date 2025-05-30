<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Controllers\History;

use App\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

final class ShowHistoryController
{
    public function __invoke(Request $request, History $history): Response
    {
        return Inertia::render('History/Show', [
            'history' => $history->load('players'),
            'invitationLink' => URL::temporarySignedRoute(
                'invitation.accept',
                Carbon::now()->addDay(),
                ['history' => $history->id],
            ),
        ]);
    }
}
