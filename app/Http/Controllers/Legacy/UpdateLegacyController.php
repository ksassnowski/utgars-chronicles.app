<?php declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\Legacy;
use App\History;
use App\Events\LegacyUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Legacy\UpdateLegacyRequest;

class UpdateLegacyController extends Controller
{
    public function __invoke(UpdateLegacyRequest $request, History $history, Legacy $legacy): RedirectResponse
    {
        $legacy->update($request->validated());

        broadcast(new LegacyUpdated($legacy))->toOthers();

        return redirect()->back();
    }
}
