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

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\CreateEchoRequest;
use App\MicroscopeEcho\Actions\AddsEcho;
use Illuminate\Http\RedirectResponse;

final class CreateEchoController
{
    public function __construct(
        private readonly AddsEcho $addEcho,
    ) {
    }

    public function __invoke(
        CreateEchoRequest $request,
        History $history,
        Event $event,
    ): RedirectResponse {
        $this->addEcho->handle(
            $request->cause(),
            $event,
            $request->name(),
            $request->type(),
        );

        broadcast(new BoardUpdated($history))->toOthers();

        return redirect()->back();
    }
}
