<?php

declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\MoveSceneRequest;
use App\Scene;
use Illuminate\Http\RedirectResponse;

final class MoveSceneController
{
    public function __invoke(MoveSceneRequest $request, History $history, Scene $scene): RedirectResponse
    {
        $scene->move($request->position());

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
