<?php declare(strict_types=1);

namespace App\Http\Controllers;

use DB;
use App\Lfg;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateLfgRequest;
use Illuminate\Database\Eloquent\Builder;

class LookingForGroupController extends Controller
{
    public function index(Request $request): Response
    {
        $this->validate($request, ['start_date' => 'date']);

        return Inertia::render('Lfg/Index', [
            'games' => Lfg::query()
                ->has('users', '<', DB::raw('lfgs.slots'))
                ->where('start_date', '>=', now())
                ->when($request->query('start_date'), function (Builder $query, $date) {
                    $query->where('start_date', '>=', $date);
                })
                ->orderBy('start_date', 'ASC')
                ->get(),
        ]);
    }

    public function show(Lfg $lfg)
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
