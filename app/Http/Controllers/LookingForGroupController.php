<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Controllers;

use App\Http\Requests\CreateLfgRequest;
use App\Lfg;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class LookingForGroupController extends Controller
{
    public function index(Request $request): Response
    {
        $this->validate($request, ['start_date' => 'date']);

        return Inertia::render('Lfg/Index', [
            'games' => Lfg::query()
                /** @phpstan-ignore-next-line */
                ->has('users', '<', DB::raw('lfgs.slots'))
                ->where('start_date', '>=', now())
                ->when($request->query('start_date'), static function (Builder $query, $date): void {
                    $query->where('start_date', '>=', $date);
                })
                ->orderBy('start_date', 'ASC')
                ->get(),
        ]);
    }

    public function show(Lfg $lfg): void
    {
    }

    public function create(): Response
    {
        return Inertia::render('Lfg/Create');
    }

    public function store(CreateLfgRequest $request): RedirectResponse
    {
        $lfg = $request->user()
            ->lfgs()
            ->create($request->validated());

        return redirect()->route('lfg.show', $lfg);
    }
}
