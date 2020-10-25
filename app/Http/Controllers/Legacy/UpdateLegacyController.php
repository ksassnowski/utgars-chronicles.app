<?php declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\Legacy;
use App\History;
use App\Events\LegacyUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Legacy\UpdateLegacyRequest;

class UpdateLegacyController extends Controller
{
    public function __invoke(UpdateLegacyRequest $request, History $history, Legacy $legacy): JsonResponse
    {
        $legacy->update($request->validated());

        broadcast(new LegacyUpdated($legacy));

        return response()->json();
    }
}
