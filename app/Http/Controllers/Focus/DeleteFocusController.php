<?php declare(strict_types=1);

namespace App\Http\Controllers\Focus;

use App\Focus;
use App\Events\FocusDeleted;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class DeleteFocusController
{
    public function __invoke(Request $request, Focus $focus): JsonResponse
    {
        $focus->delete();

        broadcast(new FocusDeleted($focus->history, $focus->id))->toOthers();

        return response()->json([], 204);
    }
}
