<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\User;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\CreateHistoryRequest;

final class StoreHistoryController
{
    public function __invoke(CreateHistoryRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->histories()->create($request->validated());

        return redirect()->route('home');
    }
}
