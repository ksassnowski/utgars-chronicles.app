<?php

declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\Events\FocusDefined;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Requests\History\DefineFocusRequest;
use Illuminate\Http\RedirectResponse;

class DefineFocusController extends Controller
{
    public function __invoke(DefineFocusRequest $request, History $history): RedirectResponse
    {
        $focus = $history->defineFocus($request->name());

        broadcast(new FocusDefined($history, $focus))->toOthers();

        return redirect()->back();
    }
}
