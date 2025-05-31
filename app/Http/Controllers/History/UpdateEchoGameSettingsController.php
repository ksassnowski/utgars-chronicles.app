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

use App\Events\EchoSettingsUpdated;
use App\History;
use App\Http\Requests\UpdateEchoGameSettingsRequest;
use Symfony\Component\HttpFoundation\Response;

final class UpdateEchoGameSettingsController
{
    public function __invoke(
        UpdateEchoGameSettingsRequest $request,
        History $history,
    ): Response {
        if (!$history->isEchoGame()) {
            return response()->json(
                ['message' => 'Game is not an Echo game'],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $history->echoGameSettings()->update($request->validated());

        broadcast(new EchoSettingsUpdated($history))->toOthers();

        return redirect()->back();
    }
}
