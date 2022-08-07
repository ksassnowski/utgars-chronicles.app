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

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\CreateInterventionRequest;
use App\MicroscopeEcho\Actions\AddsIntervention;
use Illuminate\Http\RedirectResponse;

final class CreateInterventionController
{
    public function __construct(
        private readonly AddsIntervention $addIntervention,
    ) {
    }

    public function __invoke(
        CreateInterventionRequest $request,
        History $history,
        Event $event,
    ): RedirectResponse {
        $this->addIntervention->handle(
            $event,
            $request->name(),
            $request->type(),
        );

        broadcast(new BoardUpdated($history))->toOthers();

        return redirect()->back();
    }
}
