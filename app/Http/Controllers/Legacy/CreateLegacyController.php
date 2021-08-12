<?php declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\History;
use App\Events\LegacyCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Legacy\CreateLegacyRequest;

class CreateLegacyController extends Controller
{
    public function __invoke(CreateLegacyRequest $request, History $history): RedirectResponse
    {
        $legacy = $history->addLegacy($request->get('name'));

        broadcast(new LegacyCreated($legacy))->toOthers();

        return redirect()->back();
    }
}
