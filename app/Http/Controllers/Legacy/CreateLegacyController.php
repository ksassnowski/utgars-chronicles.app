<?php declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\History;
use App\Events\LegacyCreated;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Legacy\CreateLegacyRequest;

class CreateLegacyController extends Controller
{
    public function __invoke(CreateLegacyRequest $request, History $history): JsonResponse
    {
        $legacy = $history->addLegacy($request->get('name'));

        broadcast(new LegacyCreated($legacy));

        return response()->json([], 201);
    }
}
