<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $user->load(['histories', 'games']);

        return Inertia::render('Home', [
            'histories' => $user->histories,
            'games' => $user->games,
        ]);
    }
}
