<?php declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Scene;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\UpdateSceneRequest;

final class UpdateSceneController
{
    public function __invoke(UpdateSceneRequest $request, History $history, Scene $scene): RedirectResponse
    {
        $scene->update($request->validated());

        broadcast(new BoardUpdated($scene->event->period->history))->toOthers();

        return redirect()->back();
    }
}
