<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Contracts\Database\Query\Builder;
use Inertia\Inertia;
use Inertia\Response;

final class GameController
{
    public function __invoke(History $history): Response
    {
        return Inertia::render('Game', [
            'history' => fn () => $history->load([
                'periods',
                'periods.events' => function (Builder $builder) use ($history): void {
                    $builder->whereIn('id', $this->getLatestEventIDs($history));
                },
                'periods.events.scenes',
            ]),
            'foci' => static fn () => $history->foci,
            'palettes' => static fn () => $history->palettes,
            'legacies' => static fn () => $history->legacies,
            'echoGameSettings' => static fn () => $history->echoGameSettings,
        ]);
    }

    private function getLatestEventIDs(History $history): \Closure
    {
        return static function (Builder $query) use ($history): void {
            $query
                ->select('a.id')
                ->fromSub(static function (Builder $query) use ($history): void {
                    $query
                        ->selectRaw('id, ROW_NUMBER() OVER (PARTITION BY echo_group ORDER BY echo_group_position DESC) ranked_order')
                        ->from('events')
                        ->where('history_id', $history->id);
                }, 'a')
                ->where('a.ranked_order', 1);
        };
    }
}
