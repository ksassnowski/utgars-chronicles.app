<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

final class ShowHistoryController
{
    public function __invoke(Request $request, History $history): Response
    {
        return Inertia::render('History/Show', [
            'history' => $history->load('players'),
            'invitationLink' => URL::temporarySignedRoute(
                'invitation.accept',
                Carbon::now()->addDay(),
                ['history' => $history->id]
            ),
        ]);
    }
}
