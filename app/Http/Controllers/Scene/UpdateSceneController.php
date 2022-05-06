<?php

declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\UpdateSceneRequest;
use App\Scene;
use Illuminate\Http\RedirectResponse;

final class UpdateSceneController
{
    public function __invoke(UpdateSceneRequest $request, History $history, Scene $scene): RedirectResponse
    {
        $scene->update($request->validated());

        broadcast(new BoardUpdated($scene->event->period->history))->toOthers();

        return redirect()->back();
    }
}
