<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\CreateSceneRequest;

final class CreateSceneController
{
    public function __invoke(CreateSceneRequest $request, History $history, Event $event): RedirectResponse
    {
        $event->scenes()->create(array_merge(
            $request->validated(),
            ['history_id' => $history->id]
        ));

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
