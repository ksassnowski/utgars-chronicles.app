<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\ChangeVisibilityRequest;

class ChangeVisibilityController extends Controller
{
    public function __invoke(ChangeVisibilityRequest $request, History $history): RedirectResponse
    {
        $history->update($request->validated());

        return redirect()
            ->back()
            ->with('success', __('Game visibility updated'));
    }
}
