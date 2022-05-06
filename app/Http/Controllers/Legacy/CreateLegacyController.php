<?php

declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\Events\LegacyCreated;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Requests\Legacy\CreateLegacyRequest;
use Illuminate\Http\RedirectResponse;

class CreateLegacyController extends Controller
{
    public function __invoke(CreateLegacyRequest $request, History $history): RedirectResponse
    {
        $legacy = $history->addLegacy($request->get('name'));

        broadcast(new LegacyCreated($legacy))->toOthers();

        return redirect()->back();
    }
}
