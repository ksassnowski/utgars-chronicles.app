<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use Inertia\Inertia;
use Illuminate\Http\Request;

final class CreateHistoryController
{
    public function __invoke(Request $request)
    {
        return Inertia::render('History/Create');
    }
}
