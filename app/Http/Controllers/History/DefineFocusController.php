<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use App\Events\FocusDefined;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\History\DefineFocusRequest;

class DefineFocusController extends Controller
{
    public function __invoke(DefineFocusRequest $request, History $history): JsonResponse
    {
        $focus = $history->defineFocus($request->name());

        broadcast(new FocusDefined($history, $focus))->toOthers();

        return response()->json([], 201);
    }
}
