<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Lfg;
use App\LfgRequest;
use Illuminate\Http\Request;
use App\Notifications\NewLfgRequest;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\LfgAlreadyFullException;
use App\Notifications\LfgRequestWasAccepted;
use App\Exceptions\GameAlreadyStartedException;
use App\Exceptions\AlreadyRequestedToJoinException;
use App\Exceptions\RequestAlreadyAcceptedException;

class LfgRequestController extends Controller
{
    public function index()
    {
    }

    public function store(Request $request, Lfg $lfg): RedirectResponse
    {
        try {
            $lfgRequest = $lfg->requestToJoin($request->user(), $request->post('message'));
        } catch (AlreadyRequestedToJoinException | LfgAlreadyFullException | GameAlreadyStartedException $e) {
            return $this->unsuccessfulRequestResponse($e);
        }

        $lfg->owner->notify(new NewLfgRequest($lfgRequest));

        return redirect()->route('lfg.requests.index');
    }

    public function accept(LfgRequest $lfgRequest)
    {
        try {
            $lfgRequest->accept();
        } catch (RequestAlreadyAcceptedException) {
            return redirect()->back()->withErrors([
                'request' => __('Request has already been accepted')
            ]);
        }

        $lfgRequest->user->notify(new LfgRequestWasAccepted($lfgRequest));
    }

    private function unsuccessfulRequestResponse($exception): RedirectResponse
    {
        $message = match (get_class($exception)) {
            AlreadyRequestedToJoinException::class => 'You already have a pending request for this game',
            LfgAlreadyFullException::class => 'Game is already full',
            GameAlreadyStartedException::class => 'Game has already happened',
        };

        return redirect()->back()->withErrors(['lfg' => __($message)]);
    }
}
